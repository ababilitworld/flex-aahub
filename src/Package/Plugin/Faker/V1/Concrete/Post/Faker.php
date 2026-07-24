<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Faker\V1\Concrete\Post;

use Ababilithub\FlexPhp\Package\Faker\V1\Base\Faker as BaseFaker;

class Faker extends BaseFaker
{
    public const BATCH_META_KEY = '_flex_aahub_faker_batch';

    public function generate(array $config): array
    {
        $defaults = [
            'post_type' => 'post',
            'count' => 10,
            'locale' => 'en_US',
            'seed' => null,
            'author' => get_current_user_id(),
            'post' => [
                'post_title' => ['formatter' => 'sentence', 'arguments' => [6]],
                'post_content' => ['formatter' => 'paragraphs', 'arguments' => [3, true]],
                'post_excerpt' => ['formatter' => 'sentence', 'arguments' => [16]],
                'post_status' => 'publish',
            ],
            'meta' => [],
            'taxonomies' => [],
        ];

        $config = apply_filters('flex_aahub_post_faker_config', array_replace_recursive($defaults, $config));
        $post_type = sanitize_key((string) $config['post_type']);

        if (!post_type_exists($post_type)) {
            throw new \InvalidArgumentException("Post type '{$post_type}' is not registered.");
        }

        $count = max(1, min(1000, (int) $config['count']));
        $batch_id = wp_generate_uuid4();
        $this->faker = $this->create_faker(
            sanitize_text_field((string) $config['locale']),
            $config['seed'] === null ? null : (int) $config['seed']
        );

        $result = [
            'batch_id' => $batch_id,
            'post_type' => $post_type,
            'post_ids' => [],
            'errors' => [],
        ];

        for ($index = 0; $index < $count; $index++) {
            $context = ['batch_id' => $batch_id, 'post_type' => $post_type];
            $post_data = [
                'post_type' => $post_type,
                'post_author' => (int) $config['author'],
            ];

            foreach ((array) $config['post'] as $field => $definition) {
                $post_data[$field] = $this->resolve_value($definition, $index, $context);
            }

            $post_data = apply_filters('flex_aahub_post_faker_post_data', $post_data, $index, $config, $this->faker);
            $post_id = wp_insert_post(wp_slash($post_data), true);

            if (is_wp_error($post_id)) {
                $result['errors'][] = $post_id->get_error_message();
                continue;
            }

            $context['post_id'] = $post_id;
            update_post_meta($post_id, self::BATCH_META_KEY, $batch_id);

            $meta = apply_filters('flex_aahub_post_faker_meta', (array) $config['meta'], $post_id, $index, $config);
            foreach ($meta as $meta_key => $definition) {
                update_post_meta(
                    $post_id,
                    sanitize_key((string) $meta_key),
                    $this->resolve_value($definition, $index, $context)
                );
            }

            $taxonomies = apply_filters(
                'flex_aahub_post_faker_taxonomies',
                (array) $config['taxonomies'],
                $post_id,
                $index,
                $config
            );
            $this->assign_taxonomies($post_id, $taxonomies, $index, $context);

            do_action('flex_aahub_post_faker_created', $post_id, $index, $config, $this->faker);
            $result['post_ids'][] = $post_id;
        }

        return apply_filters('flex_aahub_post_faker_result', $result, $config, $this->faker);
    }

    public function delete_batch(string $batch_id, bool $force_delete = true): int
    {
        $post_ids = get_posts([
            'post_type' => 'any',
            'post_status' => 'any',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_key' => self::BATCH_META_KEY,
            'meta_value' => sanitize_text_field($batch_id),
        ]);

        $deleted = 0;
        foreach ($post_ids as $post_id) {
            if (wp_delete_post($post_id, $force_delete)) {
                $deleted++;
            }
        }

        return $deleted;
    }

    private function assign_taxonomies(int $post_id, array $taxonomies, int $index, array $context): void
    {
        if (!$taxonomies) {
            foreach (get_object_taxonomies($context['post_type'], 'names') as $taxonomy) {
                $taxonomies[$taxonomy] = ['use_available_terms' => true];
            }
        }

        foreach ($taxonomies as $taxonomy => $definition) {
            if (is_int($taxonomy) && is_string($definition)) {
                $taxonomy = $definition;
                $definition = ['use_available_terms' => true];
            } elseif ($definition === true) {
                $definition = ['use_available_terms' => true];
            }

            $taxonomy = sanitize_key((string) $taxonomy);
            if (!taxonomy_exists($taxonomy)) {
                continue;
            }

            if (is_callable($definition)) {
                $terms = (array) $definition($this->faker, $index, $context);
                wp_set_object_terms($post_id, $terms, $taxonomy);
                continue;
            }

            $definition = is_array($definition) ? $definition : ['terms' => (array) $definition];
            $use_available_terms = !empty($definition['use_available_terms']) || !array_key_exists('terms', $definition);
            $available = $use_available_terms
                ? $this->get_available_term_ids($taxonomy)
                : (array) $definition['terms'];

            if (!$available && !empty($definition['fallback_terms'])) {
                $available = (array) $definition['fallback_terms'];
                $definition['create_missing'] = true;
            }

            if (!$available) {
                continue;
            }

            $number = max(1, min(count($available), (int) ($definition['terms_per_post'] ?? 1)));
            $selected = (array) $this->faker->randomElements($available, $number);
            $term_ids = [];

            foreach ($selected as $term) {
                if (is_numeric($term)) {
                    $term_ids[] = (int) $term;
                    continue;
                }

                $existing = term_exists((string) $term, $taxonomy);
                if (!$existing && !empty($definition['create_missing'])) {
                    $existing = wp_insert_term((string) $term, $taxonomy);
                }
                if (!is_wp_error($existing) && $existing) {
                    $term_ids[] = (int) (is_array($existing) ? $existing['term_id'] : $existing);
                }
            }

            if ($term_ids) {
                wp_set_object_terms($post_id, $term_ids, $taxonomy);
            }
        }
    }

    private function get_available_term_ids(string $taxonomy): array
    {
        $term_ids = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'fields' => 'ids',
        ]);

        if (is_wp_error($term_ids) || !$term_ids) {
            return [];
        }

        return array_values(array_map('intval', $term_ids));
    }
}

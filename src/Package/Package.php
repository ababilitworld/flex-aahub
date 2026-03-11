<?php
    namespace Ababilithub\FlexAahub\Package;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

	use Ababilithub\{
		FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
		FlexAahub\Package\Plugin\Production\Production as FlexProduction,
	};

	use const Ababilithub\{
		FlexAahub\PLUGIN_NAME,
		FlexAahub\PLUGIN_DIR,
        FlexAahub\PLUGIN_URL,
		FlexAahub\PLUGIN_FILE,
		FlexAahub\PLUGIN_VERSION
	};

	if ( ! class_exists( __NAMESPACE__.'\Package' ) ) 
	{
		/**
		 * Class Package
		 *
		 * @package Ababilithub\FlexAahub\Package
		 */
		class Package 
		{
			use StandardMixin;
	
			/**
			 * Package version
			 *
			 * @var string
			 */
			public $version = '1.0.0';

			private $test;
			private $production;	
			/**
			 * Constructor
			 */
			public function __construct($data = []) 
			{
				$this->init($data);
				register_uninstall_hook(PLUGIN_FILE, array('self', 'uninstall'));                
			}

			public function init($data)
			{
				// add_action('plugins_loaded', function () {
				// 	$this->production  = FlexProduction::getInstance();
				// });
				$this->production  = FlexProduction::getInstance();
			}
	
			/**
			 * Run the isntaller
			 * 
			 * @return void
			 */
			public static function run() 
			{
				$installed = get_option( PLUGIN_NAME.'-installed' );
	
				if ( ! $installed ) 
				{
					update_option( PLUGIN_NAME.'-installed', time() );
				}
	
				update_option( PLUGIN_NAME.'-version', PLUGIN_VERSION );
			}
	
			/**
			 * Activate The class
			 *
			 * @return void
			 */
			public static function activate(): void 
			{
				flush_rewrite_rules();
                self::run();
			}
	
			/**
			 * Dectivate The class
			 *
			 * @return void
			 */
			public static function deactivate(): void 
			{
				flush_rewrite_rules();
			}
	
			/**
			 * Uninstall the plugin
			 *
			 * @return void
			 */
			public static function uninstall(): void 
			{
				delete_option(PLUGIN_NAME . '-installed');
				delete_option(PLUGIN_NAME . '-version');
				flush_rewrite_rules();
			}	
		}

    }
	
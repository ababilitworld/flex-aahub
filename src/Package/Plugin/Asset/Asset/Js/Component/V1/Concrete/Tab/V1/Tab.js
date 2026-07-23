document.addEventListener('click', function (event) {
    const trigger = event.target.closest('[data-faih-tab] .faih-tab__link[data-tab]');
    if (!trigger) {
        return;
    }

    const component = trigger.closest('[data-faih-tab]');
    const targetId = trigger.dataset.tab;
    const target = Array.from(component.querySelectorAll('.faih-tab__content'))
        .find((panel) => panel.id === targetId);

    if (!target) {
        return;
    }

    component.querySelectorAll('.faih-tab__item').forEach((item) => item.classList.remove('active'));
    component.querySelectorAll('.faih-tab__link').forEach((link) => link.setAttribute('aria-selected', 'false'));
    component.querySelectorAll('.faih-tab__content').forEach((panel) => panel.classList.remove('active'));

    trigger.closest('.faih-tab__item').classList.add('active');
    trigger.setAttribute('aria-selected', 'true');
    target.classList.add('active');
});

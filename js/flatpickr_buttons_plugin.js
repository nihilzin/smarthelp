/**
 * ---------------------------------------------------------------------
 *
 * Powered by Urich Souza 
 *
 * https://github.com/nihilzin
 *
 * @copyright 2023 Urich Souza and contributors.
 * 
 * ---------------------------------------------------------------------
 */

window.CustomFlatpickrButtons = (config = {}) => {

    return (fp) => {
        let wrapper;

        if (config.buttons === undefined) {
            config.buttons = [{
                label: fp.config.enableTime ? __('Now') : __("Today"),
                attributes: {
                    'class': 'btn btn-outline-secondary'
                },
                onClick: (e, fp) => {
                    fp.setDate(new Date());
                }
            }];
        }

        return {
            onReady: () => {
                wrapper = `<div class="flatpickr-custom-buttons pb-1 text-start"><div class="buttons-container">`;

                (config.buttons).forEach((b, index) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.classList.add('ms-2');
                    button.innerHTML = b.label;
                    button.setAttribute('btn-id', index);
                    if (typeof b.attributes !== 'undefined') {
                        Object.keys(b.attributes).forEach((key) => {
                            if (key === 'class') {
                                button.classList.add(...b.attributes[key].split(' '));
                                return;
                            }

                            button.setAttribute(key, b.attributes[key]);
                        });
                    }

                    wrapper += button.outerHTML;

                    fp.pluginElements.push(button);
                });
                wrapper += '</div></div>';

                fp.calendarContainer.appendChild($.parseHTML(wrapper)[0]);

                $(fp.calendarContainer).on('click', '.flatpickr-custom-buttons button', (e) => {
                    e.stopPropagation();
                    e.preventDefault();

                    const btn = $(e.target);
                    const btn_id = btn.attr('btn-id');
                    const click_handler = config.buttons[btn_id].onClick;

                    if (typeof click_handler !== 'function') {
                        return;
                    }

                    click_handler(e, fp);
                });
            },

            onDestroy: () => {
                $(wrapper).remove();
            },
        };
    };
};

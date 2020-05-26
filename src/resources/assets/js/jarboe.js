const Jarboe = {
    /**
     * Displays a modal dialog with an optional message/description and buttons with their callbacks.
     */
    confirmBox: function(settings) {
        settings = $.extend({
            title: "",
            content: "",
            NormalButton: undefined,
            ActiveButton: undefined,
            buttons: undefined,
            input: undefined,
            inputValue: undefined,
            placeholder: "",
            options: undefined
        }, settings);
        const buttons = settings.buttons || {};
        settings.buttons = '['+ Object.keys(buttons).reverse().join('][') +']';

        $.SmartMessageBox(settings, function(ButtonPressed) {
            if (typeof buttons[ButtonPressed] === 'function') {
                buttons[ButtonPressed]();
            }
        });
    },

    /**
     * Show small toast notification.
     * @param settings
     * @param callback
     */
    smallToast: function(settings, callback) {
        $.smallBox(settings, callback);
    },

    /**
     * Show big toast stackable notification.
     * @param settings
     * @param callback
     */
    bigToast: function(settings, callback) {
        $.bigBox(settings, callback);
    },

    smallToastSuccess: function(title, content, timeout) {
        this.smallToast({
            title: title,
            content: content,
            timeout: timeout,
            color: '#739E73',
            icon: 'fa fa-check',
        });
    },

    smallToastDanger: function(title, content, timeout) {
        this.smallToast({
            title: title,
            content: content,
            timeout: timeout,
            color: '#C46A69',
            icon: 'fa fa-warning shake animated',
        });
    },

    smallToastWarning: function(title, content, timeout) {
        this.smallToast({
            title: title,
            content: content,
            timeout: timeout,
            color: '#C79121',
            icon: 'fa fa-shield fadeInLeft animated',
        });
    },

    smallToastInfo: function(title, content, timeout) {
        this.smallToast({
            title: title,
            content: content,
            timeout: timeout,
            color: '#3276B1',
            icon: 'fa fa-bell swing animated',
        });
    },


    /**
     * Object that hold functions for initialising plugins and stuff for fields.
     */
    initers: {},

    /**
     * Add initer.
     * @param name
     * @param initer
     */
    add: function(name, initer) {
        if (!this.initers[name]) {
            this.initers[name] = [];
        }
        this.initers[name].push(initer);
    },
    /**
     * Trigger initer.
     * @param name
     */
    init: function(name) {
        if (this.initers[name]) {
            for (initer of this.initers[name]) {
                initer();
            }
        }
    },

    /**
     * Transform camelCase and PascalCase strings into kebab-case-strings.
     * @param str
     * @returns {string}
     */
    kebabCase: function(str) {
        return str.replace(/([a-zA-Z])(?=[A-Z])/g, '$1-').toLowerCase();
    },

    /**
     * Escapes string for use in regex.
     * @param string
     * @returns {*}
     */
    escapeRegExp: function(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
    },
};
const jarboe = Jarboe;

/*
Template Name: Ubold - Responsive Bootstrap 4 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Layout
*/


/**
 * LeftSidebar
 * @param {*} $
 */
 !function ($) {
    'use strict';

    var LeftSidebar = function () {
        this.body = $('body'), this.window = $(window)
    };


    /**
     * Initilizes the menu
     */
    LeftSidebar.prototype.initMenu = function () {
        var self = this;


        // Left menu collapse
        // Todo: remove this
        // console.log($('.button-menu-mobile'))
        $('.button-menu-mobile').on('click', function (event) {
            event.preventDefault();
            // console.log("ss")
            self.body.toggleClass('sidebar-enable');
        });

        // sidebar - main menu
        if ($("#side-menu").length) {
            var navCollapse = $('#side-menu li .collapse');

            // open one menu at a time only
            navCollapse.on({
                'show.bs.collapse': function (event) {
                    var parent = $(event.target).parents('.collapse.show');
                    $('#side-menu .collapse.show').not(parent).collapse('hide');
                }
            });

            // activate the menu in left side bar (Vertical Menu) based on url
            $("#side-menu a").each(function () {
                var pageUrl = window.location.href.split(/[?#]/)[0];
                if (this.href == pageUrl) {
                    $(this).addClass("active");
                    $(this).parent().addClass("menuitem-active");
                    $(this).parent().parent().parent().addClass("show");
                    $(this).parent().parent().parent().parent().addClass("menuitem-active"); // add active to li of the current link

                    var firstLevelParent = $(this).parent().parent().parent().parent().parent().parent();
                    if (firstLevelParent.attr('id') !== 'sidebar-menu') firstLevelParent.addClass("show");

                    $(this).parent().parent().parent().parent().parent().parent().parent().addClass("menuitem-active");

                    var secondLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (secondLevelParent.attr('id') !== 'wrapper') secondLevelParent.addClass("show");

                    var upperLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (!upperLevelParent.is('body')) upperLevelParent.addClass("menuitem-active");
                }
            });
        }


        // handling two columns menu if present
        var twoColSideNav = $("#two-col-sidenav-main");
        if (twoColSideNav.length) {
            var twoColSideNavItems = $("#two-col-sidenav-main .nav-link");
            var sideSubMenus = $(".twocolumn-menu-item");

            // showing/displaying tooltip based on screen size
            // if (this.window.width() >= 585) {
            //     twoColSideNavItems.tooltip('enable');
            // } else {
            //     twoColSideNavItems.tooltip('disable');
            // }

            var nav = $('.twocolumn-menu-item .nav-second-level');
            var navCollapse = $('#two-col-menu li .collapse');

            // open one menu at a time only
            navCollapse.on({
                'show.bs.collapse': function () {
                    var nearestNav = $(this).closest(nav).closest(nav).find(navCollapse);
                    if (nearestNav.length) nearestNav.not($(this)).collapse('hide'); else navCollapse.not($(this)).collapse('hide');
                }
            });

            twoColSideNavItems.on('click', function (e) {
                var target = $($(this).attr('href'));

                if (target.length) {
                    e.preventDefault();

                    twoColSideNavItems.removeClass('active');
                    $(this).addClass('active');

                    sideSubMenus.removeClass("d-block");
                    target.addClass("d-block");

                    // showing full sidebar if menu item is clicked
                    console.log($.ThemeCustomizer);
                    if(window.themeCustomizer)
                        window.themeCustomizer.changeLeftbarSize('default');
                    return false;
                }
                return true;
            });

            // activate menu with no child
            var pageUrl = window.location.href; //.split(/[?#]/)[0];
            twoColSideNavItems.each(function () {
                if (this.href === pageUrl) {
                    $(this).addClass('active');
                }
            });


            // activate the menu in left side bar (Two column) based on url
            $("#two-col-menu a").each(function () {
                if (this.href == pageUrl) {
                    $(this).addClass("active");
                    $(this).parent().addClass("menuitem-active");
                    $(this).parent().parent().parent().addClass("show");
                    $(this).parent().parent().parent().parent().addClass("menuitem-active"); // add active to li of the current link

                    var firstLevelParent = $(this).parent().parent().parent().parent().parent().parent();
                    if (firstLevelParent.attr('id') !== 'sidebar-menu') firstLevelParent.addClass("show");

                    $(this).parent().parent().parent().parent().parent().parent().parent().addClass("menuitem-active");

                    var secondLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (secondLevelParent.attr('id') !== 'wrapper') secondLevelParent.addClass("show");

                    var upperLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (!upperLevelParent.is('body')) upperLevelParent.addClass("menuitem-active");

                    // opening menu
                    var matchingItem = null;
                    var targetEl = '#' + $(this).parents('.twocolumn-menu-item').attr("id");
                    $("#two-col-sidenav-main .nav-link").each(function () {
                        if ($(this).attr('href') === targetEl) {
                            matchingItem = $(this);
                        }
                    });
                    if (matchingItem) matchingItem.trigger('click');
                }
            });
        }
    },

        /**
         * Initilize the left sidebar size based on screen size
         */
        LeftSidebar.prototype.initLayout = function () {
            // in case of small size, activate the small menu
            //Todo --- removed
            // if ((this.window.width() >= 768 && this.window.width() <= 1028) || this.body.data('keep-enlarged')) {
            //     this.changeSize('condensed');
            // } else {
            //     this.changeSize('default');
            // }
        },

        /**
         * Initilizes the menu
         */
        LeftSidebar.prototype.init = function () {
            var self = this;
            this.initMenu();
            this.initLayout();

            // on window resize, make menu flipped automatically
            this.window.on('resize', function (e) {
                e.preventDefault();
                self.initLayout();
            });
        },

        $.LeftSidebar = new LeftSidebar, $.LeftSidebar.Constructor = LeftSidebar
}(window.jQuery),


    /**
     * Topbar
     * @param {*} $
     */
    function ($) {
        'use strict';

        var Topbar = function () {
            this.body = $('body'), this.window = $(window)
        };

        /**
         * Initilizes the menu
         */
        Topbar.prototype.initMenu = function () {
            // Serach Toggle
            $('#top-search').on('click', function (e) {
                $('#search-dropdown').addClass('d-block');
            });

            // hide search on opening other dropdown
            $('.topbar-dropdown').on('show.bs.dropdown', function () {
                $('#search-dropdown').removeClass('d-block');
            });

            //activate the menu in topbar(horizontal menu) based on url
            $(".navbar-nav a").each(function () {
                var pageUrl = window.location.href.split(/[?#]/)[0];
                if (this.href == pageUrl) {
                    $(this).addClass("active");
                    $(this).parent().addClass("active");
                    $(this).parent().parent().addClass("active");

                    $(this).parent().parent().parent().addClass("active");
                    $(this).parent().parent().parent().parent().addClass("active");
                    if ($(this).parent().parent().parent().parent().hasClass('mega-dropdown-menu')) {
                        $(this).parent().parent().parent().parent().parent().addClass("active");
                        $(this).parent().parent().parent().parent().parent().parent().addClass("active");

                    } else {
                        var child = $(this).parent().parent()[0].querySelector('.dropdown-item');
                        if (child) {
                            var pageUrl = window.location.href.split(/[?#]/)[0];
                            if (child.href == pageUrl || child.classList.contains('dropdown-toggle')) child.classList.add("active");
                        }
                    }
                    var el = $(this).parent().parent().parent().parent().addClass("active").prev();
                    if (el.hasClass("nav-link")) el.addClass('active');
                }
            });

            // Topbar - main menu
            $('.navbar-toggle').on('click', function (event) {
                $(this).toggleClass('open');
                $('#navigation').slideToggle(400);
            });


            //Horizontal Menu (For SM Screen)
            var AllNavs = document.querySelectorAll('ul.navbar-nav .dropdown .dropdown-toggle');

            var isInner = false;

            AllNavs.forEach(function (element) {
                element.addEventListener('click', function (event) {
                    if (!element.parentElement.classList.contains('nav-item')) {
                        isInner = true;
                        element.parentElement.parentElement.classList.add('show');
                        var parent = element.parentElement.parentElement.parentElement.querySelector('.nav-link');
                        parent.ariaExpanded = true;
                        parent.classList.add("show");
                        bootstrap.Dropdown.getInstance(element).show();
                    }
                });

                element.addEventListener('hide.bs.dropdown', function (event) {
                    if (isInner) {
                        event.preventDefault();
                        event.stopPropagation();
                        isInner = false;
                    }
                });
            });

        },


            /**
             * Initilizes the menu
             */
            Topbar.prototype.init = function () {
                this.initMenu();
            }, $.Topbar = new Topbar, $.Topbar.Constructor = Topbar
    }(window.jQuery),


    /**
     * RightBar
     * @param {*} $
     */
    function ($) {
        'use strict';

        var RightBar = function () {
            this.body = $('body'), this.window = $(window)
        };

        /**
         * Toggles the right sidebar
         */
        RightBar.prototype.toggleRightSideBar = function () {
            var self = this;
            self.body.toggleClass('right-bar-enabled');
        },

            /**
             * Initilizes the right side bar
             */
            RightBar.prototype.init = function () {
                var self = this;

                // right side-bar toggle
                $(document).on('click', '.right-bar-toggle', function () {
                    self.toggleRightSideBar();
                });

                $(document).on('click', 'body', function (e) {
                    // hiding search bar
                    if ($(e.target).closest('#top-search').length !== 1) {
                        $('#search-dropdown').removeClass('d-block');
                    }
                    if ($(e.target).closest('.right-bar-toggle, .right-bar').length > 0) {
                        return;
                    }

                    if ($(e.target).closest('.left-side-menu, .side-nav').length > 0 || $(e.target).hasClass('button-menu-mobile') || $(e.target).closest('.button-menu-mobile').length > 0) {
                        return;
                    }

                    $('body').removeClass('right-bar-enabled');
                    $('body').removeClass('sidebar-enable');

                });

                // overall color scheme

            },

            $.RightBar = new RightBar, $.RightBar.Constructor = RightBar
    }(window.jQuery),


    /**
     * Layout and theme manager
     * @param {*} $
     */

    function ($) {
        'use strict';

        // Layout and theme manager

        var LayoutThemeApp = function () {
        };

        LayoutThemeApp.prototype.init = function () {
            this.leftSidebar = $.LeftSidebar;
            this.leftSidebar.init()
            this.topbar = $.Topbar;

            this.themeCustomizer = $.ThemeCustomizer;
            this.themeCustomizer.init();

            this.leftSidebar.parent = this;
            this.topbar.parent = this;
            this.topbar.init()

            // initilize the menu
        },

            $.LayoutThemeApp = new LayoutThemeApp, $.LayoutThemeApp.Constructor = LayoutThemeApp


    }(window.jQuery),


    function ($) {
        'use strict';

        // Layout and theme manager

        var ThemeCustomizer = function () {
            this.body = document.body;
            this.defaultConfig = {
                leftbar: {
                    color: 'light', size: 'default'
                },
                menu: {
                    position: 'fixed'
                },
                layout: {
                    color: 'light', width: 'fluid', mode: 'default',
                },
                topbar: {
                    color: 'light'
                },
                sidebar: {
                    user: true
                }
            }
        };


        ThemeCustomizer.prototype.initConfig = function () {
            var config = JSON.parse(JSON.stringify(this.defaultConfig));
            config['leftbar']['color'] = this.body.getAttribute('data-leftbar-color') ?? this.defaultConfig.leftbar.color;
            config['leftbar']['size'] = this.body.getAttribute('data-leftbar-size') ?? this.defaultConfig.leftbar.size;
            config['menu']['position'] = this.body.getAttribute('data-leftbar-position') ?? this.defaultConfig.menu.position;
            config['layout']['color'] = this.body.getAttribute('data-layout-color') ?? this.defaultConfig.layout.color;
            config['layout']['width'] = this.body.getAttribute('data-layout-width') ?? this.defaultConfig.layout.width;
            config['layout']['mode'] = this.body.getAttribute('data-layout-mode') ?? this.defaultConfig.layout.mode;
            config['topbar']['color'] = this.body.getAttribute('data-topbar-color') ?? this.defaultConfig.topbar.color;
            config['sidebar']['user'] = this.body.getAttribute('data-sidebar-user') ?? this.defaultConfig.sidebar.user;
            this.defaultConfig = JSON.parse(JSON.stringify(config));
            // console.log(this.defaultConfig);
            this.config = config;
            this.setSwitchFromConfig();
        }

        ThemeCustomizer.prototype.changeLeftbarColor = function (color) {
            this.config.leftbar.color = color;
            this.body.setAttribute('data-leftbar-color', color);
            this.setSwitchFromConfig();
        }

        ThemeCustomizer.prototype.changeMenuPosition = function (position) {
            this.config.menu.position = position;
            this.body.setAttribute('data-menu-position', position);
            this.setSwitchFromConfig();
        }

        ThemeCustomizer.prototype.changeLeftbarSize = function (size) {
            if (this.config.layout.mode !== 'topnav') {
                this.config.leftbar.size = size;
                this.body.setAttribute('data-leftbar-size', size);
                this.setSwitchFromConfig();
            }
        }

        ThemeCustomizer.prototype.changeLayoutMode = function (mode) {
            this.config.layout.mode = mode;
            this.body.setAttribute('data-layout-mode', mode);
            this.setSwitchFromConfig();
        }

        ThemeCustomizer.prototype.changeLayoutColor = function (color) {
            this.config.layout.color = color;
            this.body.setAttribute('data-layout-color', color);
            this.setSwitchFromConfig();
        }

        ThemeCustomizer.prototype.changeLayoutWidth = function (width) {
            this.config.layout.width = width;
            this.body.setAttribute('data-layout-width', width);
            if(width=='boxed'){
                this.changeLeftbarSize('condensed');  
            } else {
                this.changeLeftbarSize('default');
            }

            this.setSwitchFromConfig();
        }

        ThemeCustomizer.prototype.changeTopbarColor = function (color) {
            this.config.topbar.color = color;
            this.body.setAttribute('data-topbar-color', color);
            this.setSwitchFromConfig();
        }

        ThemeCustomizer.prototype.changeSidebarUser = function (showUser) {
            this.config.sidebar.user = showUser;
            if (showUser) {
                this.body.setAttribute('data-sidebar-user', showUser);
            } else {
                this.body.removeAttribute('data-sidebar-user');
            }
            this.setSwitchFromConfig();
        }

        ThemeCustomizer.prototype.resetTheme = function () {
            this.config = JSON.parse(JSON.stringify(this.defaultConfig));
            this.changeLeftbarColor(this.config.leftbar.color);
            this.changeMenuPosition(this.config.menu.position);
            this.changeLeftbarSize(this.config.leftbar.size);
            this.changeLayoutColor(this.config.layout.color);
            this.changeLayoutWidth(this.config.layout.width);
            this.changeLayoutMode(this.config.layout.mode);
            this.changeTopbarColor(this.config.topbar.color);
            this.changeSidebarUser(this.config.sidebar.user);
        }

        ThemeCustomizer.prototype.initSwitchListener = function () {
            const self = this;
            document.querySelectorAll('input[name=leftbar-color]').forEach(function (element) {
                element.addEventListener('change', function (e) {
                    self.changeLeftbarColor(element.value);
                })
            });
            document.querySelectorAll('input[name=leftbar-size]').forEach(function (element) {
                element.addEventListener('change', function (e) {
                    self.changeLeftbarSize(element.value);
                })
            });
            document.querySelectorAll('input[name=menu-position]').forEach(function (element) {
                element.addEventListener('change', function (e) {
                    self.changeMenuPosition(element.value);

                })
            });
            document.querySelectorAll('input[name=layout-color]').forEach(function (element) {
                element.addEventListener('change', function (e) {
                    self.changeLayoutColor(element.value);
                })
            });
            document.querySelectorAll('input[name=layout-width]').forEach(function (element) {
                element.addEventListener('change', function (e) {
                    self.changeLayoutWidth(element.value);
                })
            });

            document.querySelectorAll('input[name=layout-mode]').forEach(function (element) {
                element.addEventListener('change', function (e) {
                    self.changeLayoutMode(element.value);
                })
            });
            document.querySelectorAll('input[name=topbar-color]').forEach(function (element) {
                element.addEventListener('change', function (e) {
                    self.changeTopbarColor(element.value);
                })
            });
            document.querySelectorAll('input[name=sidebar-user]').forEach(function (element) {
                element.addEventListener('change', function (e) {
                    self.changeSidebarUser(element.checked);
                })
            });
            document.querySelector('#resetBtn')?.addEventListener('click', function (e) {
                // console.log("w" + "")
                self.resetTheme();
            });

            document.querySelector('.button-menu-mobile')?.addEventListener('click', function () {
                if (self.config.leftbar.size === 'default') {
                    self.changeLeftbarSize('condensed');
                } else {
                    self.changeLeftbarSize('default');
                }
            });
        }

        ThemeCustomizer.prototype.initWindowSize = function () {
            let self = this;
            window.addEventListener('resize', function (e) {
                if (window.innerWidth >= 750 && window.innerWidth <= 1028) {
                    self.changeLeftbarSize('condensed');
                } else {
                    self.changeLeftbarSize('default');

                }
            })
        }


        ThemeCustomizer.prototype.setSwitchFromConfig = function () {
            document.querySelectorAll('.right-bar input[type=checkbox]').forEach(function (checkbox) {
                checkbox.checked = false;
            })
            let config = this.config;
            if (config) {
                let leftbarColorSwitch = document.querySelector('input[type=checkbox][name=leftbar-color][value=' + config.leftbar.color + ']');
                let leftbarSizeSwitch = document.querySelector('input[type=checkbox][name=leftbar-size][value=' + config.leftbar.size + ']');
                let menuPositionSwitch = document.querySelector('input[type=checkbox][name=menu-position][value=' + config.menu.position + ']');

                let layoutColorSwitch = document.querySelector('input[type=checkbox][name=layout-color][value=' + config.layout.color + ']');
                let layoutSizeSwitch = document.querySelector('input[type=checkbox][name=layout-width][value=' + config.layout.width + ']');
                let layoutModeSwitch = document.querySelector('input[type=checkbox][name=layout-mode][value=' + config.layout.type + ']');

                let topbarColorSwitch = document.querySelector('input[type=checkbox][name=topbar-color][value=' + config.topbar.color + ']');
                let sidebarUserSwitch = document.querySelector('input[type=checkbox][name=sidebar-user]');


                if (leftbarColorSwitch) leftbarColorSwitch.checked = true;
                if (leftbarSizeSwitch) leftbarSizeSwitch.checked = true;
                if (menuPositionSwitch) menuPositionSwitch.checked = true;

                if (layoutColorSwitch) layoutColorSwitch.checked = true;
                if (layoutSizeSwitch) layoutSizeSwitch.checked = true;
                if (layoutModeSwitch) layoutModeSwitch.checked = true;

                if (topbarColorSwitch) topbarColorSwitch.checked = true;
                if (sidebarUserSwitch && config.sidebar.user.toString() === "true") sidebarUserSwitch.checked = true;
            }
        }


        /**
         * Init
         */
        ThemeCustomizer.prototype.init = function () {
            this.initConfig();
            this.initSwitchListener();
            this.initWindowSize();
            window.themeCustomizer = this;
        },

            $.ThemeCustomizer = new ThemeCustomizer, $.ThemeCustomizer.Constructor = ThemeCustomizer


    }(window.jQuery);


/*
Template Name: Ubold - Responsive Bootstrap 4 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Main Js File
*/


!function ($) {
    "use strict";

    var Components = function () { };

    //initializing tooltip
    Components.prototype.initTooltipPlugin = function () {
        $.fn.tooltip && $('[data-bs-toggle="tooltip"]').tooltip()
    },

    //initializing popover
    Components.prototype.initPopoverPlugin = function () {
        $.fn.popover && $('[data-bs-toggle="popover"]').popover()
    },

    //initializing toast
    Components.prototype.initToastPlugin = function() {
        $.fn.toast && $('[data-bs-toggle="toast"]').toast()
    },

    //initializing form validation
    Components.prototype.initFormValidation = function () {
        $(".needs-validation").on('submit', function (event) {
            $(this).addClass('was-validated');
            if ($(this)[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
            return true;
        });
    },

    // Counterup
    Components.prototype.initCounterUp = function() {
        var delay = $(this).attr('data-delay')?$(this).attr('data-delay'):100; //default is 100
        var time = $(this).attr('data-time')?$(this).attr('data-time'):1200; //default is 1200
         $('[data-plugin="counterup"]').each(function(idx, obj) {
            $(this).counterUp({
                delay: delay,
                time: time
            });
         });
    },

    //peity charts
    Components.prototype.initPeityCharts = function() {
        $('[data-plugin="peity-pie"]').each(function(idx, obj) {
            var colors = $(this).attr('data-colors')?$(this).attr('data-colors').split(","):[];
            var width = $(this).attr('data-width')?$(this).attr('data-width'):20; //default is 20
            var height = $(this).attr('data-height')?$(this).attr('data-height'):20; //default is 20
            $(this).peity("pie", {
                fill: colors,
                width: width,
                height: height
            });
        });
        //donut
         $('[data-plugin="peity-donut"]').each(function(idx, obj) {
            var colors = $(this).attr('data-colors')?$(this).attr('data-colors').split(","):[];
            var width = $(this).attr('data-width')?$(this).attr('data-width'):20; //default is 20
            var height = $(this).attr('data-height')?$(this).attr('data-height'):20; //default is 20
            $(this).peity("donut", {
                fill: colors,
                width: width,
                height: height
            });
        });

        $('[data-plugin="peity-donut-alt"]').each(function(idx, obj) {
            $(this).peity("donut");
        });

        // line
        $('[data-plugin="peity-line"]').each(function(idx, obj) {
            $(this).peity("line", $(this).data());
        });

        // bar
        $('[data-plugin="peity-bar"]').each(function(idx, obj) {
            var colors = $(this).attr('data-colors')?$(this).attr('data-colors').split(","):[];
            var width = $(this).attr('data-width')?$(this).attr('data-width'):20; //default is 20
            var height = $(this).attr('data-height')?$(this).attr('data-height'):20; //default is 20
            $(this).peity("bar", {
                fill: colors,
                width: width,
                height: height
            });
         });
    },

    Components.prototype.initKnob = function() {
        $('[data-plugin="knob"]').each(function(idx, obj) {
           $(this).knob();
        });
    },

    Components.prototype.initTippyTooltips = function () {
        
        // console.log($('[data-plugin="tippy"]').length);
        if($('[data-plugin="tippy"]').length > 0){
            tippy('[data-plugin="tippy"]');
        }
    },

    Components.prototype.initShowPassword = function () {
        $("[data-password]").on('click', function() {
            if($(this).attr('data-password') == "false"){
                $(this).siblings("input").attr("type", "text");
                $(this).attr('data-password', 'true');
                $(this).addClass("show-password");
            } else {
                $(this).siblings("input").attr("type", "password");
                $(this).attr('data-password', 'false');
                $(this).removeClass("show-password");
            }
        });
    },

    Components.prototype.initMultiDropdown = function () {
        $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
            if (!$(this).next().hasClass('show')) {
              $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass('show');
    
            return false;
        });   
    },

    //initilizing
    Components.prototype.init = function () {
        this.initTooltipPlugin(),
        this.initPopoverPlugin(),
        this.initToastPlugin(),
        this.initFormValidation(),
        this.initCounterUp(),
        this.initPeityCharts(),
        this.initKnob();
        this.initTippyTooltips();
        this.initShowPassword();
        this.initMultiDropdown();
    },

    $.Components = new Components, $.Components.Constructor = Components

}(window.jQuery),

function($) {
    "use strict";

    /**
    Portlet Widget
    */
    var Portlet = function() {
        this.$body = $("body"),
        this.$portletIdentifier = ".card",
        this.$portletCloser = '.card a[data-toggle="remove"]',
        this.$portletRefresher = '.card a[data-toggle="reload"]'
    };

    //on init
    Portlet.prototype.init = function() {
        // Panel closest
        var $this = this;
        $(document).on("click",this.$portletCloser, function (ev) {
            ev.preventDefault();
            var $portlet = $(this).closest($this.$portletIdentifier);
                var $portlet_parent = $portlet.parent();
            $portlet.remove();
            if ($portlet_parent.children().length == 0) {
                $portlet_parent.remove();
            }
        });

        // Panel Reload
        $(document).on("click",this.$portletRefresher, function (ev) {
            ev.preventDefault();
            var $portlet = $(this).closest($this.$portletIdentifier);
            // This is just a simulation, nothing is going to be reloaded
            $portlet.append('<div class="card-disabled"><div class="card-portlets-loader"></div></div>');
            var $pd = $portlet.find('.card-disabled');
            setTimeout(function () {
                $pd.fadeOut('fast', function () {
                    $pd.remove();
                });
            }, 500 + 300 * (Math.random() * 5));
        });
    },
    //
    $.Portlet = new Portlet, $.Portlet.Constructor = Portlet
    
}(window.jQuery),

function ($) {
    'use strict';

    var App = function () {
        this.$body = $('body'),
        this.$window = $(window)
    };

    /** 
     * Initlizes the controls
    */
    App.prototype.initControls = function () {
        // remove loading
        setTimeout(function() {
            document.body.classList.remove('loading');
        }, 400);
        
        // Preloader
        $(window).on('load', function () {
            $('#status').fadeOut();
            $('#preloader').delay(350).fadeOut('slow');
        });

        $('[data-toggle="fullscreen"]').on("click", function (e) {
            e.preventDefault();
            $('body').toggleClass('fullscreen-enable');
            if (!document.fullscreenElement && /* alternative standard method */ !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        });
        document.addEventListener('fullscreenchange', exitHandler );
        document.addEventListener("webkitfullscreenchange", exitHandler);
        document.addEventListener("mozfullscreenchange", exitHandler);
        function exitHandler() {
            if (!document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
                // console.log('pressed');
                $('body').removeClass('fullscreen-enable');
            }
        }
    },

    //initilizing
    App.prototype.init = function () {
        $.Portlet.init();
        $.Components.init();

        this.initControls();

        // init layout
        this.layout = $.LayoutThemeApp;
        this.rightBar = $.RightBar;
        this.rightBar.layout = this.layout;
        this.layout.rightBar = this.rightBar;
    
        this.layout.init();
        this.rightBar.init(this.layout);
        

        // showing the sidebar on load if user is visiting the page first time only
        var bodyConfig = this.$body.data('layout');
        if (window.sessionStorage && bodyConfig && bodyConfig.hasOwnProperty('showRightSidebarOnPageLoad') && bodyConfig['showRightSidebarOnPageLoad']) {
            var alreadyVisited = sessionStorage.getItem("_UBOLD_VISITED_");
            if (!alreadyVisited) {
                $.RightBar.toggleRightSideBar();
                sessionStorage.setItem("_UBOLD_VISITED_", true);
            }
        }

        //Popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

        //Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        //Toasts
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl)
        })

        // Toasts Placement
        var toastPlacement = document.getElementById("toastPlacement");
        if (toastPlacement) {
            document.getElementById("selectToastPlacement").addEventListener("change", function () {
                if (!toastPlacement.dataset.originalClass) {
                    toastPlacement.dataset.originalClass = toastPlacement.className;
                }
                toastPlacement.className = toastPlacement.dataset.originalClass + " " + this.value;
            });
        }


        //  RTL support js
        if(document.getElementById('app-style').href.includes('rtl.min.css')){
            document.getElementsByTagName('html')[0].dir="rtl";
        }

        // if(document.getElementById('app-default-stylesheet').href.includes('rtl.min.css')){
        //     document.getElementsByTagName('html')[0].dir="rtl";
        // }

        // if(document.getElementById('app-dark-stylesheet').href.includes('rtl.min.css')){
        //     document.getElementsByTagName('html')[0].dir="rtl";
        // }



        // document.querySelectorAll('.checkbox').forEach(function(element){
        //     if(element.querySelector('input').getAttribute('checked')!=null){
        //         element.querySelector('input').setAttribute('checked',true)
        //         //element.click();
        //     }
        // });
    },

    $.App = new App, $.App.Constructor = App


}(window.jQuery),
//initializing main application module
function ($) {
    "use strict";
    $.App.init();
}(window.jQuery);

// Waves Effect
Waves.init();

// Feather Icons
feather.replace()
//# sourceMappingURL=app.min.js.map

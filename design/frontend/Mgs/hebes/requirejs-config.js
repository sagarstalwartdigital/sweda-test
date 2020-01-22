var config = {
    map: {
        '*': {
            html5shiv: 'js/html5shiv',
            responsive: 'js/responsive',
            theme: 'js/theme',
            slick: 'js/sweda/slick',
            noframeworkwaypoint:'js/noframework.waypoints.min',
            daterangepickerjs:'js/daterangepicker',
            maskjs:'js/sweda/jquery.mask.min'
        }
    },
    paths: {
        slick:'js/sweda/slick'
    },
    shim: {
        slick: {
            deps: ['jquery']
        },
        noframeworkwaypoint: {
            deps: ['jquery']
        },
        daterangepickerjs: {
            deps: ['jquery']
        },
        maskjs: {
            deps: ['jquery']
        }
    }
};
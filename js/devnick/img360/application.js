/*
 * jQuery File Upload Plugin JS Example 5.0.3
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 */

/*jslint nomen: true */
/*global jQuery */

jQuery(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    jQuery('#fileupload').fileupload();

    // Load existing files:
    jQuery.getJSON(jQuery('#fileupload form').prop('action'), function (files) {
        var fu = jQuery('#fileupload').data('fileupload');
        fu._adjustMaxNumberOfFiles(-files.length);
        fu._renderDownload(files)
            .appendTo(jQuery('#fileupload .files'))
            .fadeIn(function () {
                // Fix for IE7 and lower:
                jQuery(this).show();
            });
    });

    // Open download dialogs via iframes,
    // to prevent aborting current uploads:
    jQuery('#fileupload .files').delegate(
        'a:not([target^=_blank])',
        'click',
        function (e) {
            e.preventDefault();
            jQuery('<iframe style="display:none;"></iframe>')
                .prop('src', this.href)
                .appendTo('body');
        }
    );

});
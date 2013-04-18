var PrePopulatedMessages = Class.create();
PrePopulatedMessages.prototype = {
    initialize: function() {
        this.loadMessagesDropdown();
    },
	
    loadMessagesDropdown: function() {
        console.log(prePopulateMessagesUrl)
        new Ajax.Request(prePopulateMessagesUrl, {
            method: 'get',
            loaderArea: false,
            onComplete: function(transport) {
                var response = transport.responseText.evalJSON();
                var dropdown = response.dropdown;
                console.log(dropdown)
                $('history_form').down('span.field-row', 1).insert({
                    'before': dropdown
                });
                prePopulatedMessages.initMessagesDropdownListener();
            }
        });
    },
	
    initMessagesDropdownListener: function() {
        var selectedMessage = new Form.Element.Observer('prepopulated_messages_dropdown', 0, function() {
            $('history_comment').setValue(this.getValue())
        });
    }
}
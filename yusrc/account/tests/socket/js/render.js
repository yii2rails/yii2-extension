
render = {

    show: function(text, type) {
        var parentElem = $("#content");
        var div = document.createElement('div');
        div.className = "alert alert-" + type;
        div.innerHTML = text;
        parentElem.append(div);
    },

    showData: function(data) {
        var message = '<pre>' + JSON.stringify(data, "", 4) + '</pre>';
        render.show(message, 'info');
    },

    showMessage: function(text) {
        render.show(text, 'success');
    },

    showError: function(text) {
        render.show(text, 'danger');
    },

    showInfo: function(text) {
        render.show(text, 'info');
    },

    clear: function() {
        var parentElem = $("#content");
        parentElem.html("");
    },

    showStatus: function(text, type) {
        var parentElem = $("#status");
        parentElem.html(text);
        parentElem.attr('class', 'label label-' + type);
    },

    showOpenStatus: function() {
        render.showStatus('Соединение открыто', 'success');
    },

    showCloseStatus: function() {
        render.showStatus('Соединение закрыто', 'default');
    },

    showAbortStatus: function() {
        render.showStatus('Соединение прервано', 'danger');
    },

    showProgressStatus: function() {
        render.showStatus('Соединение...', 'warning');
    },

    setLogins: function(logins) {
        render.setSelectOptions(logins, 'login');
    },

    setUrls: function(urls) {
        var urlOptions = [];
        for(key in urls) {
            var data = urls[key];
            urlOptions.push(key);
        }
        render.setSelectOptions(urlOptions, 'url');
    },

    setSelectOptions: function(items, selectorId) {
        var selector = $("#" + selectorId);
        for(i in items) {
            var url = items[i];
            selector.append('<option value="' + url + '">' + url + '</option>');
        }
    },

};

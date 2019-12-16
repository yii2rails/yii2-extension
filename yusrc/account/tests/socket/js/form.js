
form = {

    getData() {
        var data = {};
        data.url = form.getValueByName('url');
        data.login = form.getValueByName('login');
        data.events = form.getValueByName('events');
        return data;
    },

    getValueByName(name) {
        return $("#" + name).val();
    },

};

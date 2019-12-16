
api = {

    baseUrl: null,

    setBaseUrl: function (baseUrl) {
        api.baseUrl = baseUrl;
    },

    sendRequest: function (request) {
        request.url = api.baseUrl + '/' + request.url;
        if(auth.identity != null) {
            request.headers = {};
            request.headers.Authorization = auth.identity.token;
        }
        $.ajax(request);
    },

};

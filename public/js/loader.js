
class Load {
    loadVew(page, parent = null) {
        $.get('/api/'+page)
            .done((res) => {
                console.log(res);
                if (parent && $(parent)) {
                    $(parent).html(res.data)
                } else {
                    $.app.pageContainer.html(res.data);
                }
                $.app.get('router').changePage(page, res.data)
            })
            .fail(function(e) {
                console.log(e);
            });
    }

    insertJsAndCSSFiles(page) {
        if (page['css']) {
            var fileCss =document.createElement("link");
            fileCss.setAttribute("rel", "stylesheet");
            fileCss.setAttribute("type", "text/css");
            fileCss.setAttribute("href", page['css']);
            $.app.pageHead.appendChild(fileCss);
        }
        if (page['js']) {
            var fileJs =document.createElement("script");
            fileJs.setAttribute("type","text/javascript");
            fileJs.setAttribute("src", page['js']);
            $.app.pageBody.appendChild(fileJs);
        }
    }
}

class Load {
    request(page, data = {}, parent = null, method = 'get', callback = null, callbackError = null, append = true) {
        console.log(data);
        $.ajax({
            url: '/api/'+page,
            type: method,
            data: data,
            success: function(res) {
                console.log(res);
                console.log(append);
                if (append === true) {
                    if (parent && $(parent)) {
                        $(parent).append(res.data);
                    } else {
                        if (page === 'plans') {
                            console.log(page);
                            $.app.pageContainer.html(res.data);
                            $.app.get('router').changePage(page, res.data)
                        }
                    }
                }
                if (callback) {
                    callback();
                }
            },
            error: function(xhr) {
                console.log(xhr);
                if (callbackError) {
                    callbackError();
                }
            }
        });
    }

    insertJsAndCSSFiles(page) {
        if (page['css']) {
            const links = document.getElementsByTagName('link');
            let foundCss = false;
            Object.keys(links).map(function(key) {
                if (links[key].getAttribute('href') === page['css']) {
                    foundCss = true;
                }
            });
            if (!foundCss) {
                const fileCss = document.createElement("link");
                fileCss.setAttribute("rel", "stylesheet");
                fileCss.setAttribute("type", "text/css");
                fileCss.setAttribute("href", page['css']);
                $.app.pageHead.appendChild(fileCss);
            }
        }
        if (page['js']) {
            const scripts = document.getElementsByTagName('script');
            let foundJs = false;
            Object.keys(scripts).map(function(key) {
                if (scripts[key].getAttribute('src') === page['js']) {
                    foundJs = true;
                }
            });
            if (!foundJs) {
                const fileJs = document.createElement("script");
                fileJs.setAttribute("type", "text/javascript");
                fileJs.setAttribute("src", page['js']);
                $.app.pageBody.appendChild(fileJs);
            }
        }
    }
}
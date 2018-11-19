
class Load {
    request(page, data = {}, parent = null, method = 'get', callback = null, callbackError = null, append = true, removeBefore = null) {
        console.log(data);
        $.ajax({
            url: '/api/'+page,
            type: method,
            data: data,
            success: function(res) {
                console.log(res);
                $.app.get('loader').lastResponse = res;
                console.log(append, removeBefore);
                if (append === true) {
                    if (parent && $(parent)) {
                        if (removeBefore) {
                            $(parent).find(removeBefore).remove();
                            $(parent).append(res.data);
                        } else {
                            $(parent).append(res.data);
                        }
                    } else {
                        $.app.pageContainer.html(res.data);
                        $.app.get('router').changePage(page, res.data)
                    }
                }
                if (callback) {
                    callback();
                }
            },
            error: function(xhr) {
                console.log(xhr);
                if (callbackError) {
                    callbackError(xhr);
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
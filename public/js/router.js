
const routes = {
    plans: {
        route: 'plans',
        css: 'css/pages/plan.css'
    },
    users: {
        route: 'users',
        css: 'css/pages/user.css'
    },
};


class Router {
    constructor() {
        const invRoute = 'invite?token=';

        const currentPage = window.location.href.substr(window.location.href.indexOf('#')+2);

        if(currentPage.indexOf(invRoute) !== -1) {
            const token = currentPage.substr(currentPage.indexOf(invRoute) + invRoute.length);
            $.app.get('loader').request('invite/check', {token: token}, null, 'post', function () {
                $.app.get('router').redirect('users');
            }, function () {
                $.app.get('router').redirect('plans');
            });
        } else {
            let found = false;
            Object.keys(routes).map(function (key) {
                if (routes[key].route === currentPage) {
                    found = true;
                }
            });
            if (!found) {
                this.redirect('plans');
            } else {
                this.redirect(currentPage);
            }
        }
    }

    redirect(page) {
        if (Object.keys(routes).includes(page)) {
            $.app.get('loader').request(routes[page].route);
            $.app.get('loader').insertJsAndCSSFiles(routes[page]);
        } else {
            console.log('Page not Found');
        }
    }

    changePage(title, html){
        document.title = $.app.get('helper').ucfirst(title);
        window.history.pushState({"html": html, "pageTitle":$.app.get('helper').ucfirst(title)},"", '#/'+title);
    }
}

window.onpopstate = function(e){
    if(e.state){
        $.app.pageContainer.html(e.state.html);
        document.title = e.state.pageTitle;
    }
};
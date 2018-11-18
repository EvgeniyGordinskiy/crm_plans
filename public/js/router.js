
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
        console.log(window.location);
        const currentPage = window.location.href.substr(window.location.href.indexOf('#')+2);
        console.log(currentPage);
        let found = false;
        Object.keys(routes).map(function(key){
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

    redirect(page) {
        console.log(page);
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
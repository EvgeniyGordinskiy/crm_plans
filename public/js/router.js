
const routes = {
    plans: {
        route: 'plans',
        js: 'js/pages/plan.js',
        css: 'css/pages/plan.css'
    },
    users: {
        route: 'users',
        js: 'js/pages/user.js',
        css: 'css/pages/user.css'
    },
};


class Router {
    constructor() {
        this.redirect('plans');
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
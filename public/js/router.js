
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
        this.url = '';
        this.redirect('plans');
    }

    redirect(page) {
        if (Object.keys(routes).includes(page)) {
            this.url += routes[page].route;
            $.app.get('loader').loadVew(this.url);
            $.app.get('loader').insertJsAndCSSFiles(routes[page]);
        } else {
            console.log('Page not Found');
        }
    }

    changePage(title, html){
        document.title = title;
        window.history.pushState({"html": html, "pageTitle":title},"", '#/'+title);
    }
}

window.onpopstate = function(e){
    if(e.state){
        $.app.pageContainer.html(e.state.html);
        document.title = e.state.pageTitle;
    }
};
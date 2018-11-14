
import Load from './loader.js'

export default class Router {
    constructor(app) {
        console.log(app);
        console.log(Router.pages);
        if (Object.keys(Router.pages).length > 0) {
            this.redirect(Router.pages[Object.keys(Router.pages)[0]]);
        }
    }

    redirect(page) {
        if (page.route && page.js) {
            Load.loadVew(page);
        }
    }
}
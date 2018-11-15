
console.log('loaded app');

class App {
    constructor() {
        this.app = $('#app');
        this.pageBody = $('body')[0];
        this.pageHead = $('head')[0];
        this.pageContainer = this.app.find('#page-content-wrapper .container');
        this.instances = {};
        console.log(this.pageContainer);
    }

    get(object) {
     switch(object) {
         case 'loader': {
             if (!this.instances['loader']) {
                 this.instances['loader'] = new Load();
             }
             return this.instances['loader'];
         }
         case 'router': {
             if (!this.instances['router']) {
                 this.instances['router'] = new Router();
             }
             return this.instances['router'];
         }
     }
    }
}

$.app = new App();
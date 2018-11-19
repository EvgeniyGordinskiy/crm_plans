
class App {
    constructor() {
        this.app = $('#app');
        this.pageBody = $('body')[0];
        this.pageHead = $('head')[0];
        this.pageContainer = this.app.find('#page-content-wrapper .container');
        this.instances = {};
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
         case 'helper': {
             if (!this.instances['helper']) {
                 this.instances['helper'] = new Helper();
             }
             return this.instances['helper'];
         }
     }
   }
}

$.app = new App();
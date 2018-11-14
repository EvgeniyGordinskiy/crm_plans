import App from '../js/App.js'
import Router from '../js/router.js'

console.log('index work');

const app = new App();

Router.pages = {
  'plans': {route: '/plans', js: '../js/pages/plan.js'},
  'users': {r: '/users', j: '../js/pages/user.js'},
};

new Router();


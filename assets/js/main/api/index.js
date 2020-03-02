import funcs from './funcs.js';
import interfaceController from './interfaceController.js';
import boxesController from './boxesController.js';

export default class {

    constructor() {
        
        var _funcs = new funcs(this);
        this.getFuncs = function() { return _funcs; };

        var _interfaceController = new interfaceController(this);
        this.getInterface = function() { return _interfaceController; };

        var _boxesController = new boxesController(this);
        this.getBoxes = function() { return _boxesController; };
    }

    setProjectApi(projectApi) {
        
        this.getProjectApi = function() { return projectApi; };
    }

    init() {

        this.getProjectApi().init();
    }

}
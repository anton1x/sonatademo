import controller from './../api/models/controller.js';
import funcs from './funcs.js';
import userInterfaceController from './userInterfaceController.js';

export default class extends controller {

    constructor(api) {
        
        super(api);

        var _userInterfaceController = new userInterfaceController(api, this);
        this.getUserInterface = function() { return _userInterfaceController; };

        var _funcs = new funcs(api, this);
        this.getFuncs = function() { return _funcs; };
    }

    init()
    {
        this.tplVars = window._initTplVars;
        this.getUserInterface().init();
    }

}
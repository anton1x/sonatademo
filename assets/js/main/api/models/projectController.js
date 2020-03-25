export default class {

    constructor(api, projectApi) {
        
        this.getApi = function() { return api; };
        this.getProjectApi = function() { return projectApi; };
    }

}
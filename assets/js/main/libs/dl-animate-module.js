export default class {
	constructor() {
		this.raf = window.requestAnimationFrame || 
				  window.webkitRequestAnimationFrame ||
				  window.msRequestAnimationFrame;		  

		let divTest = document.createElement("div");

		this.canAnimate = (typeof this.raf === "function") &&
					  ("classList" in divTest) &&
					  typeof divTest.style.transition !== undefined;

		if(this.canAnimate){
			this.raf = this.raf.bind(window);
		}

		this.frames = [];
		this.framesRun = false;
	}


	show(el, options = {}){
		if(!this.canAnimate){
			this._show(el);
		}

		let settings = this._calcOptions(options);

		if(!this._isHidden(el)){
			settings.afterEnter(el);
			return;
		}

		this._setFinishHandler(el, settings.track, settings.duration, settings.checkTargetElement, settings.lastProperty, () => {
			this._removeClasses(el, settings.classNames.enterActive);
			this._removeClasses(el, settings.classNames.enterTo);
			settings.afterEnter(el);
		});

		this._show(el);
		this._addClasses(el, settings.classNames.enter);
		settings.beforeEnter(el);
		
		this._addFrame(() => {
			this._addClasses(el, settings.classNames.enterActive);
		});

		this._addFrame(() => {
			this._removeClasses(el, settings.classNames.enter);
			this._addClasses(el, settings.classNames.enterTo);
		});
	}

	hide(el, options = {}){
		if(!this.canAnimate){
			this._hide(el);
		}

		if(this._isHidden(el)){
			settings.afterLeave(el);
			return;
		}

		let settings = this._calcOptions(options);

		this._setFinishHandler(el, settings.track, settings.duration, settings.checkTargetElement, settings.lastProperty, () => {
			this._hide(el);
			this._removeClasses(el, settings.classNames.leaveActive);
			this._removeClasses(el, settings.classNames.leaveTo);
			options.systemOnEnd && options.systemOnEnd();
			settings.afterLeave(el);
		});

		this._addClasses(el, settings.classNames.leave);
		
		settings.beforeLeave(el);
		
		this._addFrame(() => {
			this._addClasses(el, settings.classNames.leaveActive);
		});

		this._addFrame(() => {
			this._addClasses(el, settings.classNames.leaveTo);
			this._removeClasses(el, settings.classNames.leave);
		});
		
	}

	insert(target, el, options = {}, before = null){
		this._hide(el);
		target.insertBefore(el, before);
		this.show(el, options);
	}

	remove(el, options = {}){
		options.systemDoneCallback = function(){
			el.parentNode.removeChild(el);
		}

		this.hide(el, options);
	}

	_setFinishHandler(el, track, duration, checkTargetElement, lastProperty, fn){
		let eventName;
		let isCssTrack = true;

		if(track === 'transition'){
			eventName = 'transitionend';
		}
		else if(track === 'animation'){
			eventName = 'animationend';
		}
		else{
			isCssTrack = false;
		}

		if(isCssTrack){
			let handler = function(e){
				if ((checkTargetElement == false || (checkTargetElement && e.target == el)) && (lastProperty === null || (lastProperty !== null && lastProperty == e.propertyName)))
				{
					el.removeEventListener(eventName, handler);
					fn();
				}
			};

			el.addEventListener(eventName, handler);
		}
		else{
			setTimeout(fn, duration);
		}
	}

	_calcOptions(options){
		let name = (options.name !== undefined) ? options.name : 'dl-nothing-doing-class';
		let classNames = this._mergeSettings(this._classNames(name), options.classNames);

		delete options.classNames;

		let defaults = {
			name: '',
			track: 'transition',
			duration: null,
			classNames: classNames,
			checkTargetElement : true,
			lastProperty: null,
			beforeEnter(el){},
			afterEnter(el){},
			beforeLeave(el){},
			afterLeave(el){},
			systemDoneCallback(el){}
		}

		let norm = this._mergeSettings(defaults, options);

		return norm;
	}

	_classNames(name){
		return {
			enter: name + '-enter',
			enterActive: name + '-enter-active',
			enterTo: name + '-enter-to',
			leave: name + '-leave',
			leaveActive: name + '-leave-active',
			leaveTo: name + '-leave-to'
		}
	}

	_addFrame(fn){
		this.frames.push(fn);

		if(!this.framesRun){
			this._nextFrame();
		}
	}

	_nextFrame(){
		if(this.frames.length === 0){
			this.framesRun = false;
			return;
		}

		let frame = this.frames.shift();

		this.raf(() => {
			this.raf(() => {
				frame();
				this._nextFrame();
			});
		});
	}

	_addClasses(el, str){
		let arr = str.split(' ');

		for(let i = 0; i < arr.length; i++){
			el.classList.add(arr[i]);
		}
	}

	_removeClasses(el, str){
		let arr = str.split(' ');

		for(let i = 0; i < arr.length; i++){
			el.classList.remove(arr[i]);
		}
	}

	_mergeSettings(defaults, extra){
		if(typeof extra !== "object"){
			return defaults;
		}

		let res = {};

		for(let k in defaults){
			res[k] = (extra[k] !== undefined) ? extra[k] : defaults[k];
		}
		
		return res;
	}

	_hide(el){
		el.style.display = 'none';
	}

	_show(el){
		el.style.removeProperty('display');
		
		if(this._isHidden(el)){
			el.style.display = 'block';
		}
	}

	_isHidden(el){
		return this._getStyle(el, 'display') === 'none';
	}

	_getStyle(el, prop){
		return getComputedStyle(el)[prop];
	}

}
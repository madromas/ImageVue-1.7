//
//text
//
Movieclip.prototype.openWinCentre = function (url, winName, w, h, toolbar, location, directories, status, menubar, scrollbars, resizable) {
	getURL ("javascript:var myWin; if(!myWin || myWin.closed){myWin = window.open('" + url + "','" + winName + "','" + "width=" + w + ",height=" + h + ",toolbar=" + toolbar + ",location=" + location + ",directories=" + directories + ",status=" + status + ",menubar=" + menubar + ",scrollbars=" + scrollbars + ",resizable=" + resizable + ",top='+((screen.height/2)-(" + h/2 + "))+',left='+((screen.width/2)-(" + w/2 + "))+'" + "')}else{myWin.focus();};void(0);");
}
MovieClip.prototype.dotrun = function(mydottext, char, maxdot) {
	if(char == undefined){
		char = ".";
	}
	if(maxdot == undefined){
		this.maxdot = 4;
	} else {
		this.maxdot = maxdot;
	}
	this.dots = 0;
	this.dotdir = 1;
	this.onEnterFrame = function() {
		this.dots += this.dotdir;
		if (this.dots>=this.maxdot) {
			this.dotdir = -1;
		} else if (this.dots<1) {
			this.dotdir = 1;
		}
		this.tempdots = "";
		for (dc=0; dc<this.dots; dc++) {
			this.tempdots += char;
		}
		this.mytf.text = mydottext+this.tempdots;
	};
};
MovieClip.prototype.khead = function(newtext, oldtext, lspeed, blinkdelay, shadowtext, sethtml) {
	if (sethtml == undefined) {
		sethtml = true;
	}
	this.temptext = oldtext;
	this.i = oldtext.length;
	if (lspeed == null) {
		lspeed = 1;
	}
	if (blinkdelay == null) {
		blinkdelay = 31;
	}
	this.temptext = "";
	this.counter = 0;
	this.onEnterFrame = function() {
		for (mylspeed=0; mylspeed<lspeed; mylspeed++) {
			this.temptext += newtext.charAt(this.i);
			if ((newtext.charAt(this.i) == "<") and (sethtml)) {
				htmlend = newtext.indexOf(">", this.i);
				htmladd = htmlend-this.i;
				this.i += htmladd;
				this.temptext = newtext.substr(0, this.i);
			} else {
				this.i++;
			}
		}
		this.mytf.htmlText = this.temptext+"_";
		if (shadowtext) {
			this.mytf2.htmlText = this.temptext+"_";
		}
		if (this.i>=newtext.length) {
			this.mybool = 1;
			this.onEnterFrame = function() {
				this.counter++;
				this.mybool = !this.mybool;
				if (this.mybool == true) {
					this.mytf.htmlText = this.temptext+"_";
					if (shadowtext) {
						this.mytf2.htmlText = this.temptext+"_";
					}
				} else {
					this.mytf.htmlText = this.temptext;
					if (shadowtext) {
						this.mytf2.htmlText = this.temptext;
					}
				}
				if (this.counter>=blinkdelay) {
					this.mytf.htmlText = this.temptext;
					if (shadowtext) {
						this.mytf2.htmlText = this.temptext;
					}
					this.counter = 0;
					delete this.onEnterFrame;
					this.finifunc();
					this.done = true;
				}
			};
		}
	};
};
//
// motion
//
MovieClip.prototype.mset = function(prop) {
	for (i in prop) {
		this[i] = Math.round(prop[i]);
	}
}
MovieClip.prototype.mfade = function(prop, de, rem, newdepth,tracer) {
	if(de <= 1){
		this.mset(prop);
		this.finifunc();
	} else {
	this.breakloop = 0;
	if (newdepth) {
		this.swapDepths(newdepth);
	}
	this.setnum = new Array();
	for (i in prop) {
		this.setnum.push(this[i]);
	}
	this.onEnterFrame = function() {
		this.inum = 0;
		for (i in prop) {
			this.setnum[this.inum] += (prop[i]-this.setnum[this.inum])/de;
			this[i] = Math.round(this.setnum[this.inum]);
			if (Math.abs(this.setnum[this.inum]-prop[i])<1) {
				this.breakloop++;
			}
			this.inum++;
		}
		if (this.breakloop==this.inum) {
			delete this.onEnterFrame;
			if (rem) {
				if (this.getDepth()<1) {
					this.swapDepths(999);
				}
				this.removeMovieClip();
			} else {
				this.finifunc();
			}
			for (i in prop) {
				this[i] = prop[i];
			}
		} else {
			this.breakloop = 0;
		}
	};
	}
};
MovieClip.prototype.melastic = function(prop, accel, convert, rem, newdepth) {
	if (newdepth) {
		this.swapDepths(newdepth);
	}
	this.setnum = new Array();
	for (i in prop) {
		this.setnum.push(this[i]);
	}
	this.fcounter = 0;
	this.cprop = 0;
	this.onEnterFrame = function() {
		this.inum = 0;
		for (i in prop) {
			this.cprop = this.cprop*accel+(prop[i]-this.setnum[this.inum])*convert;
			this.setnum[this.inum] += this.cprop;
			this[i] = this.setnum[this.inum];
			this.inum++;
		}
		if (Math.abs(this.setnum[this.inum-1]-prop[i])<1) {
			this.fcounter++;
			if (this.fcounter>4) {
				delete this.onEnterFrame;
				if (rem) {
					if (this.getDepth()<1) {
						this.swapDepths(999);
					}
					this.removeMovieClip();
				} else {
					this.finifunc();
				}
				for (i in prop) {
					this[i] = prop[i];
				}
			}
		} else {
			this.fcounter = 0;
		}
	};
};
MovieClip.prototype.mtrig = function(prop, speed, loops, rem) {
	for (i in prop) {
		this["startpos"+i] = this[i];
		this["delta"+i] = prop[i]-this[i];
	}
	this.x = 0;
	this.onEnterFrame = function() {
		this.x += speed;
		this.c = Math.sin(this.x)*Math.sin(this.x)*Math.sin(this.x);
		for (i in prop) {
			this[i] = this["startpos"+i]+(this.c*this["delta"+i]);
		}
		if (this.x>=Math.PI*loops) {
			delete this.onEnterFrame;
			if (rem) {
				this.removeMovieClip();
			}
			this.x = 0;
			for (i in prop) {
				this[i] = prop[i];
			}
			this.finifunc();
			this._parent.mfini();
		}
	};
};
//
//color
//
MovieClip.prototype.settransstatic = function(ra, ga, ba, rb, gb, bb) {
	this.mycol = new Color(this);
	this.mycol.setTransform({ra:ra, ga:ga, ba:ba, rb:rb, gb:gb, bb:bb});
};
MovieClip.prototype.settransb = function(c, d) {
	this.mycol = new Color(this);
	if (this.sc == undefined) {
		this.sc = 0;
	}
	this.onEnterFrame = function() {
		this.sc += (c-this.sc)/d;
		this.mycol.setTransform({rb:this.sc, gb:this.sc, bb:this.sc});
		if (Math.abs(this.sc-c)<1) {
			delete this.onEnterFrame;
			this.sc = c;
			this.mycol.setTransform({rb:this.sc, gb:this.sc, bb:this.sc});
			this.finifunc();
		}
	};
};
MovieClip.prototype.settranscolz = function( r, g, b, d) {
	this.mycol = new Color(this);
	if(this.cr==undefined){
		this.cr=100;
		this.cg=100;
		this.cb=100;
	}
	this.onEnterFrame = function() {
		this.cr+=(r-this.cr)/d;
		this.cg+=(g-this.cg)/d;
		this.cb+=(b-this.cb)/d;
		this.mycol.setTransform({rb:this.cr, gb:this.cg, bb:this.cb});
		if ((Math.abs(this.cr-r)<2) and (Math.abs(this.cg-g)<2) and (Math.abs(this.cb-b)<2)) {
			delete this.onEnterFrame;
			this.cr=r;this.cg=g;this.cb=b;
			this.mycol.setTransform({rb:this.cr, gb:this.cg, bb:this.cb});
			this.finifunc();
		}
	};
};
MovieClip.prototype.cshine = function( r, g, b, d) {
	this.mycol = new Color(this);
	if(this.cr==undefined){
		this.cr=100;
		this.cg=100;
		this.cb=100;
	}
	this.onEnterFrame = function() {
		this.cr+=(r-this.cr)/d;
		this.cg+=(g-this.cg)/d;
		this.cb+=(b-this.cb)/d;
		this.mycol.setTransform({ra:this.cr, ga:this.cg, ba:this.cb});
		if ((Math.abs(this.cr-r)<2) and (Math.abs(this.cg-g)<2) and (Math.abs(this.cb-b)<2)) {
			delete this.onEnterFrame;
			this.cr=r;this.cg=g;this.cb=b;
			this.mycol.setTransform({ra:this.cr, ga:this.cg, ba:this.cb});
		}
	};
};
MovieClip.prototype.setcolor = function(col) {
	this.mycol = new Color(this);
	this.mycol.setRGB("0x"+col);
};
//
//detection
//

_global.checksubversion = function () {
	playerVersion = _root.$version;
	_global.sos = playerVersion.substring(0, 3);
	li = playerVersion.lastIndexOf(",");
	fi = playerVersion.indexOf(",");
	si = playerVersion.indexOf(",", (fi+1));
	_global.mainVersion = Number(playerVersion.substring((fi-1), fi));
	_global.subVersion = Number(playerVersion.substring((si+1), li));
};
//
// misc
//
MovieClip.prototype.square = function(x, y, w, h, col) {
	if(col==undefined){
		col=0xCCCCCC;
	}
	this.beginFill(col, 100);
	this.moveTo(x, y);
	this.lineTo(x+w, y);
	this.lineTo(x+w, y+h);
	this.lineTo(x, y+h);
	this.lineTo(x, y);
	this.endFill();
};
MovieClip.prototype.outline = function(x, y, w, h, col) {
	this.square(0, 0, w, 1, col);
	this.square(w-1, 1, 1, h-2, col);
	this.square(0, h-1, w, 1, col);
	this.square(0, 1, 1, h-2, col);
};
TextFormat.prototype.copyFormat=function(copystyle){
	for(i in copystyle){
		this[i]=copystyle[i];
	}
}
//mcomponents specific
MovieClip.prototype.mcreateoutline = function(n, w, h, d, col) {
	var o=this.createEmptyMovieClip(n, d);
	o.square(2, 0, w-4, 1, col);
	o.square(w-2, 1, 1, 1, col);
	o.square(w-1, 2, 1, h-4, col);
	o.square(w-2, h-2, 1, 1, col);
	o.square(2, h-1, w-4, 1, col);
	o.square(1, h-2, 1, 1, col);
	o.square(0, 2, 1, h-4, col);
	o.square(1, 1, 1, 1, col);
};
MovieClip.prototype.mcreateoutline2 = function(n, w, h, d, col) {
	var o=this.createEmptyMovieClip(n, d);
	o.square(1, 0, w-2, 1, col);
	o.square(w-1, 1, 1, h-2, col);
	o.square(1, h-1, w-2, 1, col);
	o.square(0, 1, 1, h-2, col);
};
MovieClip.prototype.mcreateblock2 = function(n, w, h, d, col) {
	this.mcreateoutline2(n, w, h, d, col);
	this[n].square(1,1,w-2,h-2,col);
};
Array.prototype.arraytonum = function() {
	for (var i = 0; i<this.length; i++) {
		if ((Number(this[i])/Number(this[i])) == 1) {
			this[i] = Number(this[i]);
		}
	}
};
MovieClip.prototype.dragitem = function() {
	this.maxx = Stage.width-this._width-((Stage.width-700)/2);
	this.minx = -((Stage.width-700)/2);
	this.maxy = Stage.height-this._height-((Stage.height-500)/2);
	this.miny = -((Stage.height-500)/2);
	this.offx = this._xmouse+rootalias._x;
	this.offy = this._ymouse+rootalias._y;
	this.onMouseMove = function() {
		this.dragupdate();
		this.x = _root._xmouse-this.offx;
		this.y = _root._ymouse-this.offy;
		if (this.x>this.maxx) {
			this.x = this.maxx;
		} else if (this.x<this.minx) {
			this.x = this.minx;
		}
		if (this.y>this.maxy) {
			this.y = this.maxy;
		} else if (this.y<this.miny) {
			this.y = this.miny;
		}
		this._x = Math.round(this.x);
		this._y = Math.round(this.y);
		updateAfterEvent();
	};
};
MovieClip.prototype.removestatic = function(){
	this.swapDepths(999);
	this.removeMovieClip();
}
(function(){var r=1,q=2,p=4,o={id:[{type:r,name:"id"}],classid:[{type:r,name:"classid"}],codebase:[{type:r,name:"codebase"}],pluginspage:[{type:p,name:"pluginspage"}],src:[{type:q,name:"movie"},{type:p,name:"src"},{type:r,name:"data"}],name:[{type:p,name:"name"}],align:[{type:r,name:"align"}],title:[{type:r,name:"title"},{type:p,name:"title"}],"class":[{type:r,name:"class"},{type:p,name:"class"}],width:[{type:r,name:"width"},{type:p,name:"width"}],height:[{type:r,name:"height"},{type:p,name:"height"}],hSpace:[{type:r,name:"hSpace"},{type:p,name:"hSpace"}],vSpace:[{type:r,name:"vSpace"},{type:p,name:"vSpace"}],style:[{type:r,name:"style"},{type:p,name:"style"}],type:[{type:p,name:"type"}]},n=["play","loop","menu","quality","scale","salign","wmode","bgcolor","base","flashvars","allowScriptAccess","allowFullScreen"];for(var m=0;m<n.length;m++){o[n[m]]=[{type:p,name:n[m]},{type:q,name:n[m]}]}n=["allowFullScreen","play","loop","menu"];for(m=0;m<n.length;m++){o[n[m]][0]["default"]=o[n[m]][1]["default"]=true}var l=CKEDITOR.tools.cssLength;function k(i,h,g){var a=this;var f=o[a.id];if(!f){return}var e=a instanceof CKEDITOR.ui.dialog.checkbox;for(var d=0;d<f.length;d++){var c=f[d];switch(c.type){case r:if(!i){continue}if(i.getAttribute(c.name)!==null){var b=i.getAttribute(c.name);if(e){a.setValue(b.toLowerCase()=="true")}else{a.setValue(b)}return}else{if(e){a.setValue(!!c["default"])}}break;case q:if(!i){continue}if(c.name in g){b=g[c.name];if(e){a.setValue(b.toLowerCase()=="true")}else{a.setValue(b)}return}else{if(e){a.setValue(!!c["default"])}}break;case p:if(!h){continue}if(h.getAttribute(c.name)){b=h.getAttribute(c.name);if(e){a.setValue(b.toLowerCase()=="true")}else{a.setValue(b)}return}else{if(e){a.setValue(!!c["default"])}}}}}function j(i,h,g){var u=this;var f=o[u.id];if(!f){return}var e=u.getValue()==="",d=u instanceof CKEDITOR.ui.dialog.checkbox;for(var c=0;c<f.length;c++){var b=f[c];switch(b.type){case r:if(!i||b.name=="data"&&h&&!i.hasAttribute("data")){continue}var a=u.getValue();if(e||d&&a===b["default"]){i.removeAttribute(b.name)}else{i.setAttribute(b.name,a)}break;case q:if(!i){continue}a=u.getValue();if(e||d&&a===b["default"]){if(b.name in g){g[b.name].remove()}}else{if(b.name in g){g[b.name].setAttribute("value",a)}else{var v=CKEDITOR.dom.element.createFromHtml("<cke:param></cke:param>",i.getDocument());v.setAttributes({name:b.name,value:a});if(i.getChildCount()<1){v.appendTo(i)}else{v.insertBefore(i.getFirst())}}}break;case p:if(!h){continue}a=u.getValue();if(e||d&&a===b["default"]){h.removeAttribute(b.name)}else{h.setAttribute(b.name,a)}}}}CKEDITOR.dialog.add("flash",function(d){var c=!d.config.flashEmbedTagOnly,b=d.config.flashAddEmbedTag||d.config.flashEmbedTagOnly,a,e="<div>"+CKEDITOR.tools.htmlEncode(d.lang.common.preview)+'<br><div id="cke_FlashPreviewLoader'+CKEDITOR.tools.getNextNumber()+'" style="display:none"><div class="loading">&nbsp;</div></div><div id="cke_FlashPreviewBox'+CKEDITOR.tools.getNextNumber()+'" class="FlashPreviewBox"></div></div>';return{title:d.lang.flash.title,minWidth:420,minHeight:310,onShow:function(){var B=this;B.fakeImage=B.objectNode=B.embedNode=null;a=new CKEDITOR.dom.element("embed",d.document);var i=B.getSelectedElement();if(i&&i.data("cke-real-element-type")&&i.data("cke-real-element-type")=="flash"){B.fakeImage=i;var h=d.restoreRealElement(i),g=null,f=null,J={};if(h.getName()=="cke:object"){g=h;var I=g.getElementsByTag("embed","cke");if(I.count()>0){f=I.getItem(0)}var H=g.getElementsByTag("param","cke");for(var G=0,F=H.count();G<F;G++){var E=H.getItem(G),D=E.getAttribute("name"),C=E.getAttribute("value");J[D]=C}}else{if(h.getName()=="cke:embed"){f=h}}B.objectNode=g;B.embedNode=f;B.setupContent(g,f,J,i)}},onOk:function(){var z=this;var i=null,h=null,g=null;if(!z.fakeImage){if(c){i=CKEDITOR.dom.element.createFromHtml("<cke:object></cke:object>",d.document);var f={classid:"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000",codebase:"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"};i.setAttributes(f)}if(b){h=CKEDITOR.dom.element.createFromHtml("<cke:embed></cke:embed>",d.document);h.setAttributes({type:"application/x-shockwave-flash",pluginspage:"http://www.macromedia.com/go/getflashplayer"});if(i){h.appendTo(i)}}}else{i=z.objectNode;h=z.embedNode}if(i){g={};var F=i.getElementsByTag("param","cke");for(var E=0,D=F.count();E<D;E++){g[F.getItem(E).getAttribute("name")]=F.getItem(E)}}var C={},B={};z.commitContent(i,h,g,C,B);var A=d.createFakeElement(i||h,"cke_flash","flash",true);A.setAttributes(B);A.setStyles(C);if(z.fakeImage){A.replace(z.fakeImage);d.getSelection().selectElement(A)}else{d.insertElement(A)}},onHide:function(){if(this.preview){this.preview.setHtml("")}},contents:[{id:"info",label:d.lang.common.generalTab,accessKey:"I",elements:[{type:"vbox",padding:0,children:[{type:"hbox",widths:["280px","110px"],align:"right",children:[{id:"src",type:"text",label:d.lang.common.url,required:true,validate:CKEDITOR.dialog.validate.notEmpty(d.lang.flash.validateSrc),setup:k,commit:j,onLoad:function(){var g=this.getDialog(),f=function(h){a.setAttribute("src",h);g.preview.setHtml('<embed height="100%" width="100%" src="'+CKEDITOR.tools.htmlEncode(a.getAttribute("src"))+'" type="application/x-shockwave-flash"></embed>')};g.preview=g.getContentElement("info","preview").getElement().getChild(3);this.on("change",function(h){if(h.data&&h.data.value){f(h.data.value)}});this.getInputElement().on("change",function(h){f(this.getValue())},this)}},{type:"button",id:"browse",filebrowser:"info:src",hidden:true,style:"display:inline-block;margin-top:10px;",label:d.lang.common.browseServer}]}]},{type:"hbox",widths:["25%","25%","25%","25%","25%"],children:[{type:"text",id:"width",style:"width:95px",label:d.lang.common.width,validate:CKEDITOR.dialog.validate.htmlLength(d.lang.common.invalidHtmlLength.replace("%1",d.lang.common.width)),setup:k,commit:j},{type:"text",id:"height",style:"width:95px",label:d.lang.common.height,validate:CKEDITOR.dialog.validate.htmlLength(d.lang.common.invalidHtmlLength.replace("%1",d.lang.common.height)),setup:k,commit:j},{type:"text",id:"hSpace",style:"width:95px",label:d.lang.flash.hSpace,validate:CKEDITOR.dialog.validate.integer(d.lang.flash.validateHSpace),setup:k,commit:j},{type:"text",id:"vSpace",style:"width:95px",label:d.lang.flash.vSpace,validate:CKEDITOR.dialog.validate.integer(d.lang.flash.validateVSpace),setup:k,commit:j}]},{type:"vbox",children:[{type:"html",id:"preview",style:"width:95%;",html:e}]}]},{id:"Upload",hidden:true,filebrowser:"uploadButton",label:d.lang.common.upload,elements:[{type:"file",id:"upload",label:d.lang.common.upload,size:38},{type:"fileButton",id:"uploadButton",label:d.lang.common.uploadSubmit,filebrowser:"info:src","for":["Upload","upload"]}]},{id:"properties",label:d.lang.flash.propertiesTab,elements:[{type:"hbox",widths:["50%","50%"],children:[{id:"scale",type:"select",label:d.lang.flash.scale,"default":"",style:"width : 100%;",items:[[d.lang.common.notSet,""],[d.lang.flash.scaleAll,"showall"],[d.lang.flash.scaleNoBorder,"noborder"],[d.lang.flash.scaleFit,"exactfit"]],setup:k,commit:j},{id:"allowScriptAccess",type:"select",label:d.lang.flash.access,"default":"",style:"width : 100%;",items:[[d.lang.common.notSet,""],[d.lang.flash.accessAlways,"always"],[d.lang.flash.accessSameDomain,"samedomain"],[d.lang.flash.accessNever,"never"]],setup:k,commit:j}]},{type:"hbox",widths:["50%","50%"],children:[{id:"wmode",type:"select",label:d.lang.flash.windowMode,"default":"",style:"width : 100%;",items:[[d.lang.common.notSet,""],[d.lang.flash.windowModeWindow,"window"],[d.lang.flash.windowModeOpaque,"opaque"],[d.lang.flash.windowModeTransparent,"transparent"]],setup:k,commit:j},{id:"quality",type:"select",label:d.lang.flash.quality,"default":"high",style:"width : 100%;",items:[[d.lang.common.notSet,""],[d.lang.flash.qualityBest,"best"],[d.lang.flash.qualityHigh,"high"],[d.lang.flash.qualityAutoHigh,"autohigh"],[d.lang.flash.qualityMedium,"medium"],[d.lang.flash.qualityAutoLow,"autolow"],[d.lang.flash.qualityLow,"low"]],setup:k,commit:j}]},{type:"hbox",widths:["50%","50%"],children:[{id:"align",type:"select",label:d.lang.common.align,"default":"",style:"width : 100%;",items:[[d.lang.common.notSet,""],[d.lang.common.alignLeft,"left"],[d.lang.flash.alignAbsBottom,"absBottom"],[d.lang.flash.alignAbsMiddle,"absMiddle"],[d.lang.flash.alignBaseline,"baseline"],[d.lang.common.alignBottom,"bottom"],[d.lang.common.alignMiddle,"middle"],[d.lang.common.alignRight,"right"],[d.lang.flash.alignTextTop,"textTop"],[d.lang.common.alignTop,"top"]],setup:k,commit:function(v,u,i,h,g){var f=this.getValue();j.apply(this,arguments);f&&(g.align=f)}},{type:"html",html:"<div></div>"}]},{type:"fieldset",label:CKEDITOR.tools.htmlEncode(d.lang.flash.flashvars),children:[{type:"vbox",padding:0,children:[{type:"checkbox",id:"menu",label:d.lang.flash.chkMenu,"default":true,setup:k,commit:j},{type:"checkbox",id:"play",label:d.lang.flash.chkPlay,"default":true,setup:k,commit:j},{type:"checkbox",id:"loop",label:d.lang.flash.chkLoop,"default":true,setup:k,commit:j},{type:"checkbox",id:"allowFullScreen",label:d.lang.flash.chkFull,"default":true,setup:k,commit:j}]}]}]},{id:"advanced",label:d.lang.common.advancedTab,elements:[{type:"hbox",widths:["45%","55%"],children:[{type:"text",id:"id",label:d.lang.common.id,setup:k,commit:j},{type:"text",id:"title",label:d.lang.common.advisoryTitle,setup:k,commit:j}]},{type:"hbox",widths:["45%","55%"],children:[{type:"text",id:"bgcolor",label:d.lang.flash.bgcolor,setup:k,commit:j},{type:"text",id:"class",label:d.lang.common.cssClass,setup:k,commit:j}]},{type:"text",id:"style",validate:CKEDITOR.dialog.validate.inlineStyle(d.lang.common.invalidInlineStyle),label:d.lang.common.cssStyle,setup:k,commit:j}]}]}})})();
function URShift(e,r){return e>=0?e>>r:(e>>r)+(2<<~r)}qrcode={},qrcode.imagedata=null,qrcode.width=0,qrcode.height=0,qrcode.qrCodeSymbol=null,qrcode.debug=!1,qrcode.maxImgSize=1048576,qrcode.sizeOfDataLengthInfo=[[10,9,8,8],[12,11,16,10],[14,13,16,12]],qrcode.callback=null,qrcode.decode=function(e){if(0==arguments.length){var r=document.getElementById("qr-canvas"),t=r.getContext("2d");return qrcode.width=r.width,qrcode.height=r.height,qrcode.imagedata=t.getImageData(0,0,qrcode.width,qrcode.height),qrcode.result=qrcode.process(t),null!=qrcode.callback&&qrcode.callback(qrcode.result),qrcode.result}var o=new Image;o.onload=function(){var e=document.createElement("canvas"),r=e.getContext("2d"),t=o.height,d=o.width;if(o.width*o.height>qrcode.maxImgSize){var a=o.width/o.height;d=a*(t=Math.sqrt(qrcode.maxImgSize/a))}e.width=d,e.height=t,r.drawImage(o,0,0,e.width,e.height),qrcode.width=e.width,qrcode.height=e.height;try{qrcode.imagedata=r.getImageData(0,0,e.width,e.height)}catch(e){return qrcode.result="Cross domain image reading not supported in your browser! Save it to your computer then drag and drop the file!",void(null!=qrcode.callback&&qrcode.callback(qrcode.result))}try{qrcode.result=qrcode.process(r)}catch(e){qrcode.result="error decoding QR Code"}null!=qrcode.callback&&qrcode.callback(qrcode.result)},o.src=e},qrcode.isUrl=function(e){return/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(e)},qrcode.decode_url=function(e){var r="";try{r=escape(e)}catch(t){r=e}var t="";try{t=decodeURIComponent(r)}catch(e){t=r}return t},qrcode.decode_utf8=function(e){return qrcode.isUrl(e)?qrcode.decode_url(e):e},qrcode.process=function(e){var r=(new Date).getTime(),t=qrcode.grayScaleToBitmap(qrcode.grayscale());if(qrcode.debug){for(var o=0;o<qrcode.height;o++)for(var d=0;d<qrcode.width;d++){var a=4*d+o*qrcode.width*4;qrcode.imagedata.data[a]=(t[d+o*qrcode.width],0),qrcode.imagedata.data[a+1]=(t[d+o*qrcode.width],0),qrcode.imagedata.data[a+2]=t[d+o*qrcode.width]?255:0}e.putImageData(qrcode.imagedata,0,0)}var c=new Detector(t).detect();qrcode.debug&&e.putImageData(qrcode.imagedata,0,0);for(var i=Decoder.decode(c.bits).DataByte,h="",n=0;n<i.length;n++)for(var q=0;q<i[n].length;q++)h+=String.fromCharCode(i[n][q]);(new Date).getTime();return qrcode.decode_utf8(h)},qrcode.getPixel=function(e,r){if(qrcode.width<e)throw"point error";if(qrcode.height<r)throw"point error";return point=4*e+r*qrcode.width*4,p=(33*qrcode.imagedata.data[point]+34*qrcode.imagedata.data[point+1]+33*qrcode.imagedata.data[point+2])/100,p},qrcode.binarize=function(e){for(var r=new Array(qrcode.width*qrcode.height),t=0;t<qrcode.height;t++)for(var o=0;o<qrcode.width;o++){var d=qrcode.getPixel(o,t);r[o+t*qrcode.width]=d<=e}return r},qrcode.getMiddleBrightnessPerArea=function(e){for(var r=Math.floor(qrcode.width/4),t=Math.floor(qrcode.height/4),o=new Array(4),d=0;d<4;d++){o[d]=new Array(4);for(var a=0;a<4;a++)o[d][a]=new Array(0,0)}for(var c=0;c<4;c++)for(var i=0;i<4;i++){o[i][c][0]=255;for(var h=0;h<t;h++)for(var n=0;n<r;n++){var q=e[r*i+n+(t*c+h)*qrcode.width];q<o[i][c][0]&&(o[i][c][0]=q),q>o[i][c][1]&&(o[i][c][1]=q)}}for(var g=new Array(4),l=0;l<4;l++)g[l]=new Array(4);for(c=0;c<4;c++)for(i=0;i<4;i++)g[i][c]=Math.floor((o[i][c][0]+o[i][c][1])/2);return g},qrcode.grayScaleToBitmap=function(e){for(var r=qrcode.getMiddleBrightnessPerArea(e),t=r.length,o=Math.floor(qrcode.width/t),d=Math.floor(qrcode.height/t),a=new Array(qrcode.height*qrcode.width),c=0;c<t;c++)for(var i=0;i<t;i++)for(var h=0;h<d;h++)for(var n=0;n<o;n++)a[o*i+n+(d*c+h)*qrcode.width]=e[o*i+n+(d*c+h)*qrcode.width]<r[i][c];return a},qrcode.grayscale=function(){for(var e=new Array(qrcode.width*qrcode.height),r=0;r<qrcode.height;r++)for(var t=0;t<qrcode.width;t++){var o=qrcode.getPixel(t,r);e[t+r*qrcode.width]=o}return e},Array.prototype.remove=function(e,r){var t=this.slice((r||e)+1||this.length);return this.length=e<0?this.length+e:e,this.push.apply(this,t)};
/* This script and many more are available free online at
The JavaScript Source!! http://javascript.internet.com
Created by: Dustin Diaz | http://www.dustindiaz.com/ */

/* - switched to jQuery below
var Dom = {
    get: function(el) {
	if (typeof el === 'string') {
	    return document.getElementById(el);
	} else {
	    return el;
	}
    },
    add: function(el, dest) {
	var el = this.get(el);
	var dest = this.get(dest);
	dest.appendChild(el);
    },
    remove: function(el) {
	var el = this.get(el);
	el.parentNode.removeChild(el);
    }
};
var Event = {
    add: function() {
	if (window.addEventListener) {
	    return function(el, type, fn) {
		//Dom.get(el).addEventListener(type, fn, false);
	    };
	} else if (window.attachEvent) {
	    return function(el, type, fn) {
		var f = function() {
		    fn.call(Dom.get(el), window.event);
		};
		Dom.get(el).attachEvent('on' + type, f);
	    };
	}
    }()
};
Event.add(window, 'load', function() {
	var i = 0;


	Event.add('add-element', 'click', function() {
		var el = document.createElement('p');
				el.innerHTML = '<table cellspacing=0 class="blue"><tr class="blue"><td>Option </td><td><input type="text" id = "Vote'+ i +'Chtext value= "" name="data[Vote][][chtext]"></td></tr></table>';
	

	Dom.add(el, 'content');
		Event.add('remove-element', 'click', function(e) {
			//Dom.remove(el);
		    });
	    });
    });
*/

// ping edited
jQuery(document).ready(function(){
   	console.log('test');
   	var index = 1;
   	var question_no = 2;
	

   	jQuery('#add-element').click(function(){
   		console.log('add new block');
   		var question_block = '<tr class="blue">\
								<td>Question {qno}</td> \
								<td><div class="input text">\
									<input name="data[UploadMenu][{qid}][question]" type="text" id="UploadMenu0Question"></div>\
								</td>\
							</tr>\
						<tr class="blue">\
							<td>Choice</td> \
							<td> <div class="input text">\
								<input name="data[UploadMenu][{qid}][choice]" type="text" id="UploadMenu0Choice"></div>\
							</td>\
						</tr>\
						<tr class="blue">\
							<td colspan="2" class="formComment">Choice must be in format n-m e.g. 0-3 or 1-5. Leave blank if not applicable</td>\
						</tr>\
						<tr class="blue">\
							<td>Please select File that you want to upload</td> \
							<td><div class="input file">\
								<input type="file" name="data[UploadMenu][{qid}][file]" size="50" id="UploadMenu0File"></div>\
							</td>\
						</tr>\
						<tr class="blue">\
							<td>Taking action when answer</td> \
							<td> <div class="input text">\
								<input name="data[UploadMenu][{qid}][action_key]" type="text" id="UploadMenu0ActionKey"></div>\
							</td>\
						</tr>\
						<tr class="blue">\
							<td colspan="2" class="formComment">Poll action would be taken if recipient response to this question by this answer. Leave blank if not applicable. </td>\
						</tr>';

   		jQuery('.question_form').append(question_block.replace(/{qid}/g, index).replace(/{qno}/g, question_no));

   		index = index + 1;
   		question_no = question_no + 1; 

   	});
});

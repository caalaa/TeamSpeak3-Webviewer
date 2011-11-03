/*
This file is part of jQuery UI Themes
Copyright (C) 2011  Maximilian Narr

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Textbox
$.fn.TextBox = function() {
		$(this).addClass("ui-widget ui-corner-all ui-widget-content ui-textbox");
		$(this).hover(function(){
				//$(this).addClass("ui-state-hover");
			},
			function(){
				//$(this).removeClass("ui-state-hover");	
			})
}

// Fieldset
$.fn.FieldSet = function () {
		$(this).addClass("ui-corner-all ui-widget ui-state-default");	
	}
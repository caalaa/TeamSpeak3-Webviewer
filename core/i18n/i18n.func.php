<?php

/**
 *  This file is part of devMX TeamSpeak3 Webviewer.
 *  Copyright (C) 2011 - 2012 Max Rath and Maximilian Narr
 *
 *  devMX TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with devMX TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Echos given string
 * @since 0.9
 * @param type $string
 * @return type 
 */
function __e($string)
{
    echo __($string);
}

/**
 * Sets the locale
 * @since 0.9
 * @param type $locale
 * @param type $domain
 * @param type $newPath 
 */
function setL10n($locale, $domain, $newPath = NULL)
{
    $domain = $domain . "-" . strtolower($locale);
    _setlocale(LC_MESSAGES, $locale);

    if ($newPath == NULL) _bindtextdomain($domain, l10nDir);
    else _bindtextdomain($domain, $newPath);

    _textdomain($domain);
    _bind_textdomain_codeset($domain, "UTF-8");
}

?>

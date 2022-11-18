<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace OrangeHRM\Buzz\Dto\BuzzVideoURL;

use OrangeHRM\Buzz\Exception\InvalidURLException;

class EmbeddedURLForYoutube extends AbstractBuzzVideoURL
{
    private const YOUTUBE_EMBEDDED_REGEX = '/(https|http):\/\/(?:www\.)?youtube.com\/embed\/[A-z0-9]+/';
    private const YOUTUBE_REGEX = '/(?:http:|https:)*?\/\/(?:www\.|)(?:youtube\.com|m\.youtube\.com|youtu\.|youtube-nocookie\.com).*(?:v=|v%3D|v\/|(?:a|p)\/(?:a|u)\/\d.*\/|watch\?|vi(?:=|\/)|\/embed\/|oembed\?|be\/|e\/)([^&?%#\/\n]*)/';

    /**
     * @return string|null
     * @throws InvalidURLException
     */
    public function getEmbeddedURL(): ?string
    {
        if (!($this->getTextHelper()->strContains($this->getURL(), 'youtube')
            || $this->getTextHelper()->strContains($this->getURL(), 'youtu.be'))
        ) {
            return null;
        }

        if (preg_match(self::YOUTUBE_EMBEDDED_REGEX, $this->getURL())) {
            return $this->getURL();
        }

        if (preg_match(self::YOUTUBE_REGEX, $this->getURL())) {
            $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
            $longUrlRegex = '/youtube.com\/((?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

            $youtube_id = null;
            if (preg_match($longUrlRegex, $this->getURL(), $matches)) {
                $youtube_id = $matches[count($matches) - 1];
            }

            if (preg_match($shortUrlRegex, $this->getURL(), $matches)) {
                $youtube_id = $matches[count($matches) - 1];
            }

            if ($youtube_id != null) {
                return 'https://www.youtube.com/embed/' . $youtube_id;
            } else {
                throw InvalidURLException::invalidYouTubeURLProvided();
            }
        } else {
            throw InvalidURLException::invalidYouTubeURLProvided();
        }
    }
}
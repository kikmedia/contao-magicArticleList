<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleArticleList
 *
 * Extends Front end module "article list".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleScrollableArticleList extends ModuleArticleList
{

    /**
     * Generate the module
     */
    protected function compile()
    {
        global $objPage;

        if (!strlen($this->inColumn))
        {
            $this->inColumn = 'main';
        }

        $intCount = 0;
        $articles = array();
        $id = $objPage->id;

        $this->Template->request = $this->Environment->request;

        // Show articles of a different page
        if ($this->defineRoot && $this->rootPage > 0)
        {
            $objTarget = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
                                        ->limit(1)
                                        ->execute($this->rootPage);

            if ($objTarget->numRows)
            {
                $id = $this->rootPage;
                $this->Template->request = $this->generateFrontendUrl($objTarget->row());
            }
        }

        $time = time();

        // Get published articles
        $objArticles = $this->Database->prepare("SELECT id, title, alias, inColumn, cssID FROM tl_article WHERE pid=? AND inColumn=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 AND NOT exclude_article_list=1" : "") . " ORDER BY sorting")
                                      ->execute($id, $this->inColumn);

        if ($objArticles->numRows < 1)
        {
            return;
        }

        while ($objArticles->next())
        {
            // Skip first article
            if (++$intCount <= intval($this->skipFirst))
            {
                continue;
            }

            $cssID = deserialize($objArticles->cssID, true);
            $alias = strlen($objArticles->alias) ? $objArticles->alias : $objArticles->title;

            $articles[] = array
            (
                'link' => $objArticles->title,
                'title' => specialchars($objArticles->title),
                'id' => strlen($cssID[0]) ? $cssID[0] : standardize($alias),
                'articleId' => $objArticles->id
            );
        }

        $this->Template->articles = $articles;
    }
}

?>
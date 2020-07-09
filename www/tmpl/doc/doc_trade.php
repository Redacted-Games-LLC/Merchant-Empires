<?php
/**
 * 
 *
 * @package [Redacted]Me
 * ---------------------------------------------------------------------------
 *
 * Merchant Empires by [Redacted] Games LLC - A space merchant game of war
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

	include_once('tmpl/common.php');
?>
<div class="header2 header_bold">
	Trade
</div>
<div class="docs_text">
	Trade involves buying goods from one place and selling them to another. Most trade
	will occur between <a href="docs.php?page=ports">Ports</a> but there are also goods
	available at some <a href="docs.php?page=dealers">Dealers</a>.
</div>
<div class="docs_text">
	The <a href="docs.php?page=quick">Quick Start Guide</a> contains a tutorial which
	combines some features of trade, navigation and warps.
</div>
<div class="header3 header_bold">Currency</div>
<div class="docs_text">
	The common currency of the galaxy is the <em>credit</em> using the symbol
	<strong>¢</strong> (alt code 0162) and the icon
	<img src="res/credits.png" width="14" height="14" title="Credits Icon" alt="¢" />.
	This is a digital currency, no physical credits exist and all transactions
	are controlled. Private trading is done with <a href="docs.php?page=goods">goods</a>.
</div>
<div class="header3 header_bold">Inflation</div>
<div class="docs_text">
	The top-right corner of every page has an inflation indicator along
	with a page build. It looks like the following:
</div>
<div class="docs_image">
	<img src="res/doc/page_build.png" alt="Example of Inflation/Page Build" title="Inflation/Page Build" />
</div>
<div class="docs_text">
	Inflation accumulates from the start of the round at a rate of about 3% per day. After
	about a month of play prices will be twice as high as when the round began. Since all
	prices in the galaxy are modified by inflation it makes more sense to stockpile goods
	rather than banking credits.
</div>
<div class="docs_text">
	You must plan for residual inflation when using Dealers, carry a bit of extra credits
	in case the inflation shifts the price a bit while you are browsing the stock.
</div>
<div class="header3 header_bold">
	Taxes
</div>
<div class="docs_text">
	Goods and tech you purchase may be taxed. Each <a href="docs.php?page=race">race</a> has a
	different tax rate. If you trade at ports owned by your own race you will pay only one tax.
	Trading at ports owned by other races will also cost you the enemy tax rate.
</div>
<div class="docs_text">
	<a href="docs.php?page=xollian">Xollians</a> do not pay a racial tax of their own but are
	still subject to taxation when trading in enemy systems.
</div>
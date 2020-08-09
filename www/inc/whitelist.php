<?php 
/**
 * Contains whitelisted pages used in array checks to prevent injection attacks
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


// templates

$tmpl_adm_array = ["build", "gold", "good", "goods", "main", "news", "ports", "research", "room", "system", "user", "users"];

$tmpl_alliance_array = ["create", "list", "main", "members", "player", "players"];

$tmpl_doc_array = ["alignment", "alliance", "base_defense", "base_package", "bases", "bots", "combat", "contributing", "credits", "damage", "drones", "gold", "good", "goods", "hosting", "levels", "license", "main", "mawlor", "mines", "nav", "ordnance", "players", "policy", "ports", "privacy", "quick", "races", "roadmap", "scanner", "ship_rating", "ships", "tech", "terms", "trade", "turns", "warps", "weapons", "xollian", "zycklirg"];

$tmpl_msg_array = ["alliance", "inbox", "main", "player", "subspace"];

$tmpl_ship_array = ["attack", "deploy", "main", "weapons"];

// handlers

$hndl_array = ["alliance", "attack", "base", "common", "create_player", "dealer", "deselect_player", "generate_galaxy", "gold", "good", "index", "level", "login", "logout", "message", "move", "news", "port", "reset_ports", "room", "scan", "select_player", "ship", "signup", "target", "users", "warp", "weapon"];
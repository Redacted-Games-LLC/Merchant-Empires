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

$hndl_sub_alliance_array = ["create", "enroll", "leave", "recruit", "reject", "request"];

$hndl_sub_base_array = ["build", "land", "learn", "move"];

$hndl_sub_gold_array = ["add", "enable", "insert", "obtain", "remove", "transfer"];

$hndl_sub_msg_array = ["alliance", "delete", "hide", "ignore", "player", "subspace"];

$hndl_sub_room_array = ["add", "add_requirement", "delete", "delete_requirement", "edit"];

$hndl_sub_ship_array = ["deploy", "deploy_armor", "deploy_base_package", "deploy_drones", "deploy_mines", "deploy_port_package", "deploy_shields", "deploy_solar_collectors", "empty_cargo", "pickup", "rename"];

$hndl_sub_users_array = ["field"];

$hndl_sub_weapon_array = ["add", "move", "remove"];

?>
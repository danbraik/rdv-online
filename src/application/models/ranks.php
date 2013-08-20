<?php
class Ranks extends CI_Model { 
	
	const VISITOR = 0;
	const MEMBER = 2;
	const EDITOR = 128;
	const ADMIN = 256;
	
	public function grMember() {
		return Ranks::MEMBER;
	}
	
	public function grSecretary() {
		return Ranks::EDITOR;
	}
	
	public function grAdmin() {
		return Ranks::ADMIN;
	}
}

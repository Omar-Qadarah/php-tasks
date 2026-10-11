<?php
class Registration{
    public string $name;
    public string $email;
    public int $mobile;
     public string $governorate;
     public string $submeted_track;
     public array $skills;
     public string $message;
    public function __construct(string $name, string $email, int $mobile, string $governorate, string $submeted_track, array $skills, string $message){
        $this->name = $name;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->governorate = $governorate;
        $this->submeted_track = $submeted_track;
        $this->skills = $skills;
        $this->message = $message;
    }
}

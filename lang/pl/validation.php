<?php

return [
    'accepted' => 'Pole :attribute musi zostać zaakceptowane.',
    'array' => 'Pole :attribute musi być tablicą.',
    'boolean' => 'Pole :attribute musi mieć wartość prawda albo fałsz.',
    'confirmed' => 'Potwierdzenie pola :attribute nie jest zgodne.',
    'email' => 'Pole :attribute musi być poprawnym adresem e-mail.',
    'exists' => 'Wybrana wartość dla :attribute jest nieprawidłowa.',
    'file' => 'Pole :attribute musi być plikiem.',
    'in' => 'Wybrana wartość dla :attribute jest nieprawidłowa.',
    'integer' => 'Pole :attribute musi być liczbą całkowitą.',
    'max' => [
        'array' => 'Pole :attribute może zawierać maksymalnie :max elementów.',
        'file' => 'Pole :attribute nie może być większe niż :max KB.',
        'numeric' => 'Pole :attribute nie może być większe niż :max.',
        'string' => 'Pole :attribute nie może mieć więcej niż :max znaków.',
    ],
    'mimes' => 'Pole :attribute musi być plikiem typu: :values.',
    'min' => [
        'array' => 'Pole :attribute musi zawierać co najmniej :min elementów.',
        'file' => 'Pole :attribute musi mieć co najmniej :min KB.',
        'numeric' => 'Pole :attribute musi mieć co najmniej :min.',
        'string' => 'Pole :attribute musi mieć co najmniej :min znaków.',
    ],
    'numeric' => 'Pole :attribute musi być liczbą.',

    'password' => [
        'letters' => 'Pole :attribute musi zawierać co najmniej jedną literę.',
        'mixed' => 'Pole :attribute musi zawierać co najmniej jedną dużą i jedną małą literę.',
        'numbers' => 'Pole :attribute musi zawierać co najmniej jedną cyfrę.',
        'symbols' => 'Pole :attribute musi zawierać co najmniej jeden znak specjalny.',
        'uncompromised' => 'To hasło pojawiło się w wycieku danych. Wybierz inne hasło.',
    ],

    'required' => 'Pole :attribute jest wymagane.',
    'string' => 'Pole :attribute musi być tekstem.',
    'unique' => 'Taka wartość pola :attribute już istnieje.',

    'attributes' => [
        'name' => 'imię i nazwisko',
        'email' => 'adres e-mail',
        'password' => 'hasło',
        'password_confirmation' => 'potwierdzenie hasła',
        'title' => 'temat',
        'description' => 'opis problemu',
        'attachments' => 'załączniki',
        'subject' => 'temat',
        'message' => 'wiadomość',
        'service_id' => 'usługa',
        'price_from' => 'cena od',
        'turnaround_time' => 'czas realizacji',
    ],
];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute muss aktzeptiert sein.',
    'active_url' => ':attribute ist keine valide URL.',
    'after' => ':attribute muss ein Datum nach dem :date sein.',
    'after_or_equal' => ':attribute muss ein Datum nach oder zum :date sein.',
    'alpha' => ':attribute darf nur Buchstaben enthalten.',
    'alpha_dash' => ':attribute darf nur Buchstaben, Nummern, Bindestriche und Unterstriche enthalten.',
    'alpha_num' => ':attribute darf nur Buchstaben und Nummern enthalten.',
    'array' => ':attribute muss ein Array sein.',
    'before' => ':attribute muss ein Datum vor dem :date sein.',
    'before_or_equal' => ':attribute muss ein Datum vor oder zum :date sein.',
    'between' => [
        'numeric' => ':attribute muss zwischen :min und :max liegen.',
        'file' => ':attribute muss zwischen :min und :max Kilobytes groß sein.',
        'string' => ':attribute muss zwischen :min und :max Zeichen haben.',
        'array' => ':attribute muss zwischen :min und :max Einträge haben.',
    ],
    'boolean' => ':attribute muss entweder wahr oder falsch sein.',
    'confirmed' => ':attribute Bestätigung stimmt nicht überein.',
    'date' => ':attribute ist kein valides Datum.',
    'date_equals' => ':attribute muss ein Datum sein welches gleich zu :date ist.',
    'date_format' => ':attribute passt nicht zum Format :format.',
    'different' => ':attribute und :other müssen unterschiedlich sein.',
    'digits' => ':attribute muss :digits Ziffern sein.',
    'digits_between' => ':attribute muss zwischen :min und :max Ziffern haben.',
    'dimensions' => ':attribute hat eine falsche Bildgröße.',
    'distinct' => ':attribute Feld hat einen doppelten Inhalt.',
    'email' => ':attribute muss eine valide Emailadresse sein.',
    'ends_with' => ':attribute muss mit einem der folgenden enden: :values',
    'exists' => 'Das ausgewählte :attribute ist invalid.',
    'file' => ':attribute muss eine Datei sein.',
    'filled' => ':attribute muss einen Wert besitzen.',
    'gt' => [
        'numeric' => ':attribute muss größer sein als :value.',
        'file' => ':attribute muss größer als :value Kilobytes sein.',
        'string' => ':attribute muss mehr als :value Zeichen haben.',
        'array' => ':attribute muss mehr als :value Einträge haben.',
    ],
    'gte' => [
        'numeric' => ':attribute muss größer oder gleich :value sein.',
        'file' => ':attribute muss größer oder gleich :value Kilobytes groß sein.',
        'string' => ':attribute muss mehr als :value oder gleich viele Zeichen haben.',
        'array' => ':attribute muss :value Einträge oder mehr haben.',
    ],
    'image' => ':attribute muss ein Bild sein.',
    'in' => 'Das ausgewählte :attribute ist nicht gültig.',
    'in_array' => ':attribute existiert nicht in :other.',
    'integer' => ':attribute muss eine ganze Zahl sein.',
    'ip' => ':attribute muss eine valide IP-Adresse sein.',
    'ipv4' => ':attribute muss eine valide IPV4-Adresse sein.',
    'ipv6' => ':attribute muss eine valide IPV6-Adresse sein.',
    'json' => ':attribute muss muss valides JSON sein.',
    'lt' => [
        'numeric' => ':attribute muss weniger als :value sein.',
        'file' => ':attribute muss weniger als :value Kilobytes groß sein.',
        'string' => ':attribute muss weniger als :value Zeichen haben.',
        'array' => ':attribute muss weniger als :value Einträge haben.',
    ],
    'lte' => [
        'numeric' => ':attribute muss weniger oder gleich :value sein.',
        'file' => ':attribute muss kleiner als :value oder gleich Kilobytes sein.',
        'string' => ':attribute muss kleiner als :value oder gleicher Anzahl an Zeichen sein.',
        'array' => ':attribute darf nicht mehr als :value Elemente besitzen.',
    ],
    'max' => [
        'numeric' => ':attribute darf nicht größer sein als :max.',
        'file' => ':attribute darf nicht größer sein als :max Kilobytes.',
        'string' => ':attribute darf nicht größer sein als :max Zeichen.',
        'array' => ':attribute darf nicht mehr als :max Elemente besitzen.',
    ],
    'mimes' => ':attribute muss eine Datei sein vom Typ: :values.',
    'mimetypes' => ':attribute muss eine Datei sein vom Typ: :values.',
    'min' => [
        'numeric' => ':attribute muss mindestens :min sein.',
        'file' => ':attribute muss mindestens :min Kilobytes groß sein.',
        'string' => ':attribute muss mindestens :min Zeichen lang sein.',
        'array' => ':attribute muss mindestens :min Elemente haben.',
    ],
    'not_in' => 'Das ausgewählte :attribute ist ungültig.',
    'not_regex' => 'Das Format von :attribute ist ungültig.',
    'numeric' => ':attribute muss eine Nummer sein.',
    'password' => 'Das Passwort ist falsch.',
    'present' => 'Das :attribute Feld muss vorhanden sein.',
    'regex' => 'Das Format von :attribute ist ungültig.',
    'required' => 'Das :attribute Feld muss angegeben werden.',
    'required_if' => 'Das :attribute Feld muss angegeben werden, wenn :other den Wert :value hat.',
    'required_unless' => 'Das :attribute Feld muss angegeben werden, wenn :other nicht in :values vorhanden ist.',
    'required_with' => 'Das :attribute Feld muss angegeben werden, wenn :values vorhanden ist.',
    'required_with_all' => 'Das :attribute Feld muss angegeben werden, wenn :values vorhanden sind.',
    'required_without' => 'Das :attribute Feld muss angegeben werden, wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das :attribute Feld muss angegeben werden, wenn keines der Felder :values vorhanden sind.',
    'same' => ':attribute und :other müssen gleich sein.',
    'size' => [
        'numeric' => ':attribute muss :size sein.',
        'file' => ':attribute muss :size Kilobytes groß sein.',
        'string' => ':attribute muss :size Zeichen lang sein.',
        'array' => ':attribute muss :size Elemente beinhalten.',
    ],
    'starts_with' => ':attribute muss mit einem der folgenden Werte starten: :values',
    'string' => ':attribute muss eine Zeichenkette sein.',
    'timezone' => ':attribute muss eine gültige Zeitzone sein.',
    'unique' => ':attribute ist schon benutzt.',
    'uploaded' => 'Für :attribute schlug der Upload fehl.',
    'url' => 'Das Format für :attribute ist ungültig.',
    'uuid' => ':attribute muss eine gültige UUID sein.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];

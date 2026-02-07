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

    'accepted' => 'يجب قبول الحقل :attribute.',
    'accepted_if' => 'يجب قبول الحقل :attribute عندما يكون :other هو :value.',
    'active_url' => 'يجب أن يكون الحقل :attribute رابطًا إلكترونيًا صحيحًا.',
    'after' => 'يجب أن يكون الحقل :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون الحقل :attribute تاريخًا بعد أو يساوي :date.',
    'alpha' => 'يجب أن يحتوي الحقل :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي الحقل :attribute على أحرف، أرقام، شرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي الحقل :attribute على أحرف وأرقام فقط.',
    'any_of' => 'الحقل :attribute غير صالح.',
    'array' => 'يجب أن يكون الحقل :attribute مصفوفة.',
    'ascii' => 'يجب أن يحتوي الحقل :attribute على أحرف وأرقام ورموز من بايت واحد فقط.',
    'before' => 'يجب أن يكون الحقل :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون الحقل :attribute تاريخًا قبل أو يساوي :date.',
    'between' => [
        'array' => 'يجب أن يحتوي الحقل :attribute على عدد عناصر بين :min و :max.',
        'file' => 'يجب أن يكون حجم الحقل :attribute بين :min و :max كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute بين :min و :max.',
        'string' => 'يجب أن يكون عدد أحرف الحقل :attribute بين :min و :max.',
    ],
    'boolean' => 'يجب أن يكون الحقل :attribute صحيح أو خطأ.',
    'can' => 'يحتوي الحقل :attribute على قيمة غير مصرح بها.',
    'confirmed' => 'تأكيد الحقل :attribute غير متطابق.',
    'contains' => 'يفتقد الحقل :attribute إلى قيمة مطلوبة.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'يجب أن يكون الحقل :attribute تاريخًا صحيحًا.',
    'date_equals' => 'يجب أن يكون الحقل :attribute تاريخًا مطابقًا لـ :date.',
    'date_format' => 'يجب أن يطابق الحقل :attribute الشكل :format.',
    'decimal' => 'يجب أن يحتوي الحقل :attribute على :decimal منازل عشرية.',
    'declined' => 'يجب رفض الحقل :attribute.',
    'declined_if' => 'يجب رفض الحقل :attribute عندما يكون :other هو :value.',
    'different' => 'يجب أن يكون الحقل :attribute و :other مختلفين.',
    'digits' => 'يجب أن يحتوي الحقل :attribute على :digits رقمًا.',
    'digits_between' => 'يجب أن يحتوي الحقل :attribute على عدد من الأرقام بين :min و :max.',
    'dimensions' => 'أبعاد الصورة في الحقل :attribute غير صحيحة.',
    'distinct' => 'الحقل :attribute يحتوي على قيمة مكررة.',
    'doesnt_contain' => 'يجب ألا يحتوي الحقل :attribute على أي من القيم التالية: :values.',
    'doesnt_end_with' => 'يجب ألا ينتهي الحقل :attribute بأحد القيم التالية: :values.',
    'doesnt_start_with' => 'يجب ألا يبدأ الحقل :attribute بأحد القيم التالية: :values.',
    'email' => 'يجب أن يكون الحقل :attribute بريدًا إلكترونيًا صحيحًا.',
    'encoding' => 'يجب أن يكون ترميز الحقل :attribute هو :encoding.',
    'ends_with' => 'يجب أن ينتهي الحقل :attribute بأحد القيم التالية: :values.',
    'enum' => 'القيمة المحددة للحقل :attribute غير صحيحة.',
    'exists' => 'القيمة المحددة للحقل :attribute غير موجودة.',
    'extensions' => 'يجب أن يحتوي الحقل :attribute على أحد الامتدادات التالية: :values.',
    'file' => 'يجب أن يكون الحقل :attribute ملفًا.',
    'filled' => 'يجب إدخال قيمة في الحقل :attribute.',
    'gt' => [
        'array' => 'يجب أن يحتوي الحقل :attribute على أكثر من :value عنصر.',
        'file' => 'يجب أن يكون حجم الحقل :attribute أكبر من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute أكبر من :value.',
        'string' => 'يجب أن يكون عدد أحرف الحقل :attribute أكبر من :value.',
    ],
    'gte' => [
        'array' => 'يجب أن يحتوي الحقل :attribute على :value عنصر أو أكثر.',
        'file' => 'يجب أن يكون حجم الحقل :attribute أكبر من أو يساوي :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute أكبر من أو تساوي :value.',
        'string' => 'يجب أن يكون عدد أحرف الحقل :attribute أكبر من أو يساوي :value.',
    ],
    'hex_color' => 'يجب أن يكون الحقل :attribute لونًا سداسيًا صحيحًا.',
    'image' => 'يجب أن يكون الحقل :attribute صورة.',
    'in' => 'القيمة المختارة للحقل :attribute غير صحيحة.',
    'in_array' => 'الحقل :attribute غير موجود في :other.',
    'in_array_keys' => 'يجب أن يحتوي الحقل :attribute على مفتاح واحد على الأقل من القيم التالية: :values.',
    'integer' => 'يجب أن يكون الحقل :attribute عددًا صحيحًا.',
    'ip' => 'يجب أن يكون الحقل :attribute عنوان IP صحيحًا.',
    'ipv4' => 'يجب أن يكون الحقل :attribute عنوان IPv4 صحيحًا.',
    'ipv6' => 'يجب أن يكون الحقل :attribute عنوان IPv6 صحيحًا.',
    'json' => 'يجب أن يكون الحقل :attribute نص JSON صحيح.',
    'list' => 'يجب أن يكون الحقل :attribute قائمة.',
    'lowercase' => 'يجب أن يكون الحقل :attribute بحروف صغيرة.',
    'lt' => [
        'array' => 'يجب ألا يحتوي الحقل :attribute على أكثر من :value عنصر.',
        'file' => 'يجب أن يكون حجم الحقل :attribute أقل من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute أقل من :value.',
        'string' => 'يجب أن يكون عدد أحرف الحقل :attribute أقل من :value.',
    ],
    'lte' => [
        'array' => 'يجب ألا يحتوي الحقل :attribute على أكثر من :value عنصر.',
        'file' => 'يجب أن يكون حجم الحقل :attribute أقل من أو يساوي :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute أقل من أو تساوي :value.',
        'string' => 'يجب أن يكون عدد أحرف الحقل :attribute أقل من أو يساوي :value.',
    ],
    'mac_address' => 'يجب أن يكون الحقل :attribute عنوان MAC صالح.',
    'max' => [
        'array' => 'يجب ألا يحتوي الحقل :attribute على أكثر من :max عنصر.',
        'file' => 'يجب ألا يكون حجم الحقل :attribute أكبر من :max كيلوبايت.',
        'numeric' => 'يجب ألا تكون قيمة الحقل :attribute أكبر من :max.',
        'string' => 'يجب ألا يكون عدد أحرف الحقل :attribute أكبر من :max.',
    ],
    'max_digits' => 'يجب ألا يحتوي الحقل :attribute على أكثر من :max رقم.',
    'mimes' => 'يجب أن يكون الحقل :attribute ملفًا من نوع: :values.',
    'mimetypes' => 'يجب أن يكون الحقل :attribute ملفًا من نوع: :values.',
    'min' => [
        'array' => 'يجب أن يحتوي الحقل :attribute على الأقل على :min عنصر.',
        'file' => 'يجب ألا يقل حجم الحقل :attribute عن :min كيلوبايت.',
        'numeric' => 'يجب ألا تقل قيمة الحقل :attribute عن :min.',
        'string' => 'يجب ألا يقل عدد أحرف الحقل :attribute عن :min.',
    ],
    'min_digits' => 'يجب أن يحتوي الحقل :attribute على الأقل على :min رقم.',
    'missing' => 'يجب أن يكون الحقل :attribute غير موجود.',
    'missing_if' => 'يجب أن يكون الحقل :attribute غير موجود عندما يكون :other هو :value.',
    'missing_unless' => 'يجب أن يكون الحقل :attribute غير موجود ما لم يكن :other هو :value.',
    'missing_with' => 'يجب أن يكون الحقل :attribute غير موجود إذا كانت :values موجودة.',
    'missing_with_all' => 'يجب أن يكون الحقل :attribute غير موجود إذا كانت :values موجودة.',
    'multiple_of' => 'يجب أن تكون قيمة الحقل :attribute من مضاعفات :value.',
    'not_in' => 'القيمة المختارة للحقل :attribute غير صحيحة.',
    'not_regex' => 'تنسيق الحقل :attribute غير صحيح.',
    'numeric' => 'يجب أن يكون الحقل :attribute رقمًا.',
    'password' => [
        'letters' => 'يجب أن يحتوي الحقل :attribute على حرف واحد على الأقل.',
        'mixed' => 'يجب أن يحتوي الحقل :attribute على حرف كبير وصغير على الأقل.',
        'numbers' => 'يجب أن يحتوي الحقل :attribute على رقم واحد على الأقل.',
        'symbols' => 'يجب أن يحتوي الحقل :attribute على رمز واحد على الأقل.',
        'uncompromised' => 'القيمة المدخلة في الحقل :attribute ظهرت في تسريب بيانات. يرجى اختيار :attribute مختلف.',
    ],
    'present' => 'يجب أن يكون الحقل :attribute موجودًا.',
    'present_if' => 'يجب أن يكون الحقل :attribute موجودًا عندما يكون :other هو :value.',
    'present_unless' => 'يجب أن يكون الحقل :attribute موجودًا إلا إذا كان :other هو :value.',
    'present_with' => 'يجب أن يكون الحقل :attribute موجودًا إذا كانت :values موجودة.',
    'present_with_all' => 'يجب أن يكون الحقل :attribute موجودًا إذا كانت :values موجودة.',
    'prohibited' => 'الحقل :attribute ممنوع.',
    'prohibited_if' => 'الحقل :attribute ممنوع عندما يكون :other هو :value.',
    'prohibited_if_accepted' => 'الحقل :attribute ممنوع عندما يكون :other مقبولاً.',
    'prohibited_if_declined' => 'الحقل :attribute ممنوع عندما يكون :other مرفوضاً.',
    'prohibited_unless' => 'الحقل :attribute ممنوع إلا إذا كان :other ضمن :values.',
    'prohibits' => 'الحقل :attribute يمنع وجود :other.',
    'regex' => 'تنسيق الحقل :attribute غير صحيح.',
    'required' => 'الحقل :attribute مطلوب.',
    'required_array_keys' => 'يجب أن يحتوي الحقل :attribute على مفاتيح: :values.',
    'required_if' => 'الحقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_if_accepted' => 'الحقل :attribute مطلوب عندما يكون :other مقبولاً.',
    'required_if_declined' => 'الحقل :attribute مطلوب عندما يكون :other مرفوضاً.',
    'required_unless' => 'الحقل :attribute مطلوب إلا إذا كان :other ضمن :values.',
    'required_with' => 'الحقل :attribute مطلوب عندما تكون :values موجودة.',
    'required_with_all' => 'الحقل :attribute مطلوب عندما تكون :values موجودة.',
    'required_without' => 'الحقل :attribute مطلوب عندما لا تكون :values موجودة.',
    'required_without_all' => 'الحقل :attribute مطلوب عندما لا تكون أي من :values موجودة.',
    'same' => 'يجب أن يطابق الحقل :attribute الحقل :other.',
    'size' => [
        'array' => 'يجب أن يحتوي الحقل :attribute على :size عنصر.',
        'file' => 'يجب أن يكون حجم الحقل :attribute :size كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute :size.',
        'string' => 'يجب أن يحتوي الحقل :attribute على :size حرفًا.',
    ],
    'starts_with' => 'يجب أن يبدأ الحقل :attribute بأحد القيم التالية: :values.',
    'string' => 'يجب أن يكون الحقل :attribute نصًا.',
    'timezone' => 'يجب أن يكون الحقل :attribute نطاقًا زمنيًا صحيحًا.',
    'unique' => 'قيمة الحقل :attribute مستخدمة من قبل.',
    'uploaded' => 'فشل في رفع الحقل :attribute.',
    'uppercase' => 'يجب أن يكون الحقل :attribute بحروف كبيرة.',
    'url' => 'يجب أن يكون الحقل :attribute رابطًا صحيحًا.',
    'ulid' => 'يجب أن يكون الحقل :attribute ULID صالح.',
    'uuid' => 'يجب أن يكون الحقل :attribute UUID صالح.',

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

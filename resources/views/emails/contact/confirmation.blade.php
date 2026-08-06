<x-email-shell
    title="Potwierdzenie wysłania wiadomości"
    heading="Dziękujemy za kontakt"
    summary="Twoja wiadomość została wysłana i przekazana do obsługi serwisu."
    :action-url="route('public.contact')"
    action-text="Strona kontaktowa"
>
    <p style="margin:0 0 14px 0;">Witaj {{ $name }},</p>
    <p style="margin:0 0 14px 0;"><strong style="color:#facc15;">Temat:</strong> {{ $subjectLine }}</p>
    <p style="margin:0;">Odpowiemy na podany adres e-mail tak szybko, jak będzie to możliwe.</p>
</x-email-shell>

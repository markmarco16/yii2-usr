<p>
    Ta wiadomość zawiera instrukcje, jak zweryfikować ten adres email. Została wysłana na polecenie użytkownika w serwisie <?= \yii\helpers\Html::a(Yii::$app->name, $siteUrl); ?>. Jeśli nie jesteś adresatem tej wiadomości, prosimy o jej zignorowanie lub kontakt z naszym administratorem.
</p>

<p>Aby zweryfikować ten adres email, otwórz poniższy link:</p>
<p>
<?= \yii\helpers\Html::a($actionUrl, $actionUrl); ?>
</p>
<p>
Jeśli link nie otwiera się poprawnie, spróbuj skopiować go i wkleić w pasek adresu swojej przeglądarki.
</p>

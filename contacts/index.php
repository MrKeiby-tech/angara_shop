<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Контакты компании «Форест»");
$APPLICATION->SetPageProperty("keywords", "контакт телефон адрес Форест компания");
$APPLICATION->SetPageProperty("description", "Компания «Форест» - это профессиональный коллектив оказывающий услуги по продаже и производству пилорамы . Здесь Вы найдете контактную информацию.");
$APPLICATION->SetTitle("Контакты");?>

<?CMax::ShowPageType('page_contacts');?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
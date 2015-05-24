-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 18 Lis 2013, 12:20
-- Wersja serwera: 5.1.72
-- Wersja PHP: 5.3.2-1ubuntu4.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `crm`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `administratorzy`
--

CREATE TABLE IF NOT EXISTS `administratorzy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `haslo` char(40) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `tel` char(15) COLLATE utf8_polish_ci NOT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `administratorzy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `banki`
--

CREATE TABLE IF NOT EXISTS `banki` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(15) COLLATE utf8_polish_ci NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `nip` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `kod_poczt` char(6) COLLATE utf8_polish_ci DEFAULT NULL,
  `miejsowosc` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `ul` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_b` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_l` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `tel` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `banki`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `banki_oddzialy`
--

CREATE TABLE IF NOT EXISTS `banki_oddzialy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_id` int(11) NOT NULL,
  `symbol` char(15) COLLATE utf8_polish_ci NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `nip` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `kod_poczt` char(6) COLLATE utf8_polish_ci DEFAULT NULL,
  `miejscowosc` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `ul` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_b` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_l` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `tel` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `banki_oddzialy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `banki_oddz_firmy_oddz`
--

CREATE TABLE IF NOT EXISTS `banki_oddz_firmy_oddz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firma_oddzial_id` int(11) NOT NULL,
  `bank_oddzial_id` int(11) NOT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `banki_oddz_firmy_oddz`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `dokumenty_produktu`
--

CREATE TABLE IF NOT EXISTS `dokumenty_produktu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produkt_id` int(11) NOT NULL,
  `slownik_id` int(11) NOT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `dokumenty_produktu`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `dokumenty_slownik`
--

CREATE TABLE IF NOT EXISTS `dokumenty_slownik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(15) COLLATE utf8_polish_ci NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `dokumenty_slownik`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `dokumenty_zadania`
--

CREATE TABLE IF NOT EXISTS `dokumenty_zadania` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slownik_id` int(11) NOT NULL,
  `zadanie` int(11) NOT NULL,
  `adnotacje` text COLLATE utf8_polish_ci NOT NULL,
  `data_dostarczenia` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `dokumenty_zadania`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `firmy`
--

CREATE TABLE IF NOT EXISTS `firmy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(15) COLLATE utf8_polish_ci NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `nip` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `kod_poczt` char(6) COLLATE utf8_polish_ci DEFAULT NULL,
  `miejsowosc` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `ul` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_b` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_l` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `tel` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `firmy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `firmy_oddzialy`
--

CREATE TABLE IF NOT EXISTS `firmy_oddzialy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firma_id` int(11) NOT NULL,
  `symbol` char(15) COLLATE utf8_polish_ci NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `nip` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `kod_poczt` char(6) COLLATE utf8_polish_ci DEFAULT NULL,
  `miejscowosc` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `ul` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_b` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_l` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `tel` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `firmy_oddzialy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `klienci`
--

CREATE TABLE IF NOT EXISTS `klienci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazwisko` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `imie` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `pesel` char(11) COLLATE utf8_polish_ci DEFAULT NULL,
  `nip` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `kod_poczt` char(6) COLLATE utf8_polish_ci DEFAULT NULL,
  `miejscowosc` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `ul` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_b` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_l` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `telkom` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `teldom` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `telpraca` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `opis` text COLLATE utf8_polish_ci,
  `zrodlo` int(11) DEFAULT NULL,
  `firma_id` int(11) DEFAULT NULL,
  `data_kont` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `klienci`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `klienci_status`
--

CREATE TABLE IF NOT EXISTS `klienci_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `opis` varchar(200) COLLATE utf8_polish_ci NOT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `klienci_status`
--

INSERT INTO `klienci_status` (`id`, `status`, `opis`, `create`, `update`) VALUES
(1, -1, 'KLIENT_NIEZAINTERESOWANY', '2013-10-24 19:21:06', 0),
(2, 0, 'KLIENT_NIESPRAWDZONY', '2013-10-24 19:21:06', 0),
(3, 1, 'KLIENT_MOZEKIEDYS', '2013-10-24 20:46:46', 0),
(4, 2, 'KLIENT_ZASTANAWIAJACY', '2013-10-24 20:46:46', 0),
(5, 3, 'KLIENT_ZAINTERESOWANY', '2013-10-24 20:47:38', 0),
(6, 4, 'KLIENT_ZDECYDOWANY', '2013-10-24 20:47:38', 0),
(7, 5, 'KLIENT_PROCEDOWANY', '2013-10-24 20:48:00', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `liderzy`
--

CREATE TABLE IF NOT EXISTS `liderzy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stanowisko_id` int(11) NOT NULL,
  `symbol` varchar(15) COLLATE utf8_polish_ci NOT NULL,
  `opis` text COLLATE utf8_polish_ci,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `liderzy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `osoby_powiazane`
--

CREATE TABLE IF NOT EXISTS `osoby_powiazane` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klient_id` int(11) NOT NULL,
  `nazwisko` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `imie` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `pesel` char(11) COLLATE utf8_polish_ci NOT NULL,
  `nip` char(15) COLLATE utf8_polish_ci NOT NULL,
  `kod_poczt` char(6) COLLATE utf8_polish_ci DEFAULT NULL,
  `miejscowosc` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `ul` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_b` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_l` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `telkom` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `teldom` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `telpraca` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `opis` text COLLATE utf8_polish_ci,
  `firma_id` int(11) DEFAULT NULL,
  `data_kont` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `osoby_powiazane`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `pracownicy`
--

CREATE TABLE IF NOT EXISTS `pracownicy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazwisko` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `imie` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `pesel` char(11) COLLATE utf8_polish_ci NOT NULL,
  `kod_poczt` char(6) COLLATE utf8_polish_ci DEFAULT NULL,
  `miejscowosc` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `ul` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_b` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `nr_l` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `tel` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `pracownicy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `produkty`
--

CREATE TABLE IF NOT EXISTS `produkty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_id` int(11) NOT NULL,
  `symbol` varchar(15) COLLATE utf8_polish_ci NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `opis` text COLLATE utf8_polish_ci,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `produkty`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `stanowiska`
--

CREATE TABLE IF NOT EXISTS `stanowiska` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(15) COLLATE utf8_polish_ci NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `placowka_id` int(11) NOT NULL,
  `pracownik_id` int(11) NOT NULL,
  `tel` char(15) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `stanowiska`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `stanowiska_status`
--

CREATE TABLE IF NOT EXISTS `stanowiska_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `symbol` varchar(15) COLLATE utf8_polish_ci NOT NULL,
  `opis` varchar(150) COLLATE utf8_polish_ci DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `stanowiska_status`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `tabele`
--

CREATE TABLE IF NOT EXISTS `tabele` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazwa` char(30) COLLATE utf8_polish_ci NOT NULL,
  `opis` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=29 ;

--
-- Zrzut danych tabeli `tabele`
--

INSERT INTO `tabele` (`id`, `nazwa`, `opis`) VALUES
(1, 'tabele', 'Słownik tabel w bazie'),
(2, 'updates', 'Tabela przechowująca czasy ostatnich zmian w tabelach Wykorzystywana jest podczas odpytywa-\r\nnia przez przeglądarkę o czasy ostatnich modyfikacji bazy danych. Pole „tabele_id” wskazuje na re-\r\nkord w słowniku gdzie zapisana jest nazwa tabeli. Pierwszy rekord tej tabeli wskazuje na nią samą.\r\nA jej pola oznaczają wtedy: „update” - czas jakiejkolwiek modyfikacji w tej tabeli i jeżeli jest on młod-\r\nszy od „create” i „delete” to oznacza tylko aktualizację w tabeli. Aby znaleźć jaki to rekord należy\r\nprzeszukać tą tabelę i znaleźć rekord, w którym „update” ma taką samą wartość, tabele_id tego re-\r\nkordu wskazuje zmodyfikowaną tabelę. Jeżeli „update” pierwszego rekordu ma tę samą wartość co\r\n„create” pierwszego rekordu to znaczy, że ostatnią zmianą było dodanie rekordu do tabeli, w której\r\n„create” ma tą samą wartość. Z polem „delete” jest analogicznie.\r\n\r\n'),
(3, 'uprawnienia_grup', 'Wpisy dodawane są wraz z tworzeniem tabel. System kontroluje prawa dostępu\r\nto tabel dla każdego typu użytkownika. Podstawowa klasa zarządzająca dostępem do bazy, przed\r\nwykonaniem operacji sprawdza czy zalogowany użytkownik ma do tej operacji uprawnienia i w ra-\r\nzie braku zwraca wyjątek.\r\n'),
(4, 'administratorzy', ''),
(5, 'firmy', 'Jednostka organizacyjna prowadząca działalność gospodarczą.'),
(6, 'firmy_oddzialy', 'Miejsce prowadzenia działalności gospodarczej przez firmę. Firma może prowadzić działalność w\r\nwielu placówkach.\r\n'),
(7, 'zarzadcy', 'Osoby kierujące pracą firm. Jest to podzbiór pracowników. Informacje są przechowywane we wspól-\r\nnej tabeli z pracownikami szeregowymi bo zarządca też może być pracownikiem ale podkreślić że\r\nniektórzy są również zarządcami zgrupowanie są w tej tabeli. Jeżeli pracownik nie ma już uprawnie-\r\nnia to pole „aktywny” ma wartość false. Gdy w momencie odbierania uprawnień nie był jeszcze\r\nprzypisany do żadnego zarządu to rekord ten jest kasowany\r\n'),
(8, 'zarzady', 'Osoby zarządzające przypisane do kierowania pracą konkretnej firmy.\r\n'),
(9, 'stanowiska', 'Stanowiska pracy w placówkach. Od jego statusu zależy jakie czynności może wykonywać pracow-\r\nnik zatrudniony na tym stanowisku. Status stanowiska określa pole „status” którego wartość pobie-\r\nrana jest ze zbioru ograniczonego tabelą „stanowiska_status”\r\n'),
(10, 'stanowiska_status', 'Słownik statusów stanowisk pracy. Służy za źródło wartości pola „status”. Tabela „stanowiska” listę\r\nmożliwych statusów stanowiska pobiera z pola „status” tej tabeli\r\nStatusy stanowiska pracy według definicji z rozdziału 2\r\n'),
(11, 'pracownicy', 'Osoby fizyczne które są lub kiedykolwiek były zatrudnione na jakimkolwiek stanowisku.\r\n'),
(12, 'liderzy', 'Opis stanowiska pełniącego jakąkolwiek funkcję przywódczą wśród pracowników. Jednocześnie jest\r\nnagłówkiem do tworzenia zespołu podległego liderowi. Lider nie musi mieć przyporządkowanego\r\nzespołu.\r\nData w polu „data_od” jest datą utworzenia lidera.\r\nData w polu „data_do” jest ostatnią datą funkcjonowania lidera Ustawiając ją należy również ustawić\r\nwszystkie pola „data_do” w rekordach tabeli „zespoly” których pole „lider_id” wskazuje na ten re-\r\nkord.\r\n'),
(13, 'zespoly', 'Lista stanowisk podległych liderowi.\r\n11\r\nData w polu „data_od” jest datą dodania stanowiska do zespołu.\r\nData w polu „data_do” jest datą usunięcia stanowiska z zespołu.\r\n'),
(14, 'zatrodnienia', 'Historia pracy pracownika na stanowiskach. Wymagane by móc śledzić kontakty z klientami w cza-\r\nsie gdy na stanowisku zmieniali się pracownicy. Nie jest edytowana wprost przez użytkownika. Jej\r\nwpisy powstają podczas przypisywania pracowników do stanowisk i zwalniania ich.\r\n'),
(15, 'banki', 'Tabela opisująca centrale banków. Służy do zorganizowania oddziałów banków i ofert pochodzą-\r\ncych z jednego banku.\r\n'),
(16, 'banki_oddzialy', 'W praktyce nie współpracuje się z bankiem a z jego oddziałem w którymś z miast. W tej tabeli zapi-\r\nsane są wszystkie oddziały z którymi współpracuje choć jedna placówka zarejestrowana w systemie.\r\n'),
(17, 'produkty', 'Tabela gromadzi wszystkie produkty możliwe do zaoferowania przez uczestników systemu. Produk-\r\nty przypisane są do banków ale sprzedawane są przez oddziały.\r\n'),
(18, 'banki_oddz_firmy_oddz', 'Związanie placówek agencyjnych z oddziałami banków z którymi współpracują. Dzięki temu wiąza-\r\nniu placówka ma dostęp do produktów oferowanych przez bank do którego należy ten oddział.\r\n'),
(19, 'klienci', 'Osoby lub firmy których dane zostały pozyskane do kontaktu. Rekordy tej tabeli służą do dalszej pra-\r\ncy z klientami. Lub gdy klient nie wyraża zgody informuje o tym status.\r\n'),
(20, 'klienci_status', 'Tabela zawierające zbiór możliwych statusów jakie można przypisać klientowi podczas kontaktu.\r\nStatusy klienta:\r\nKLIENT_NIEZAINTERESOWANY  - -1\r\nKLIENT_NIESPRAWDZONY      - 0\r\nKLIENT_MOZEKIEDYS         - 1\r\nKLIENT_ZASTANAWIAJACY     - 2\r\nKLIENT_ZAINTERESOWANY     - 3\r\nKLIENT_ZDECYDOWANY        - 4\r\nKLIENT_PROCEDOWANY        - 5 \r\n'),
(21, 'osoby_powiazane', 'Osoby, które znalazły się na dokumentach dostarczonych przez osobę zapisaną w tabeli „klienci”.\r\n'),
(22, 'dokumenty_produktu', 'Lista dokumentów wymagana przez bank do sprzedania produktu\r\n'),
(23, 'dokumenty_zadania', 'Dokumenty dostarczane przez klientów w związku ze sprzedażą produktów\r\n'),
(24, 'dokumenty_slownik', 'Słownik dla kolumny „slownik_id” w tabelach : „dokumenty_produktu”, „dokumenty_klienta”.\r\n'),
(25, 'zadania', 'Zdania zaplanowane, realizowane i zrealizowane opisane są w tej tabeli. Pole „status” zawiera kod\r\nzakończenia kroku a „opis” zawiera komentarz do tego kroku. Kolejne kroki zadania złączone są po-\r\nlem „zadanie” które jest tej samej wartości co „id” dla pierwszego rekordu opisującego ciąg zadań.\r\nDzięki temu wiemy że początek zadania jest gdy „id” == „zadanie” a gdy szukamy opisu przebiegu\r\nzadania to szukamy wszystkich rekordów o tej samej wartości „zadanie”. Zadanie jest zakończone\r\ngdy „data_next_step” nie jest już ustawiona na żadną wartość a datą zakończenia jest „data_do” pole\r\n„status” wskazuje wówczas na ostateczny sposób zakończenia zadania. Lista dokumentów potrzeb-\r\nnych do sprzedania produktu jest w tabeli „dokumenty_klienta” w rekordach gdzie pole „zdanie”\r\nrówna się polu „zadanie” w tym rekordzie.\r\n'),
(26, 'zadania_opis', 'Wspólne notatki dla całego zadania\r\n'),
(27, 'zadania_firmy', 'Przechowuje zadania nie przypisane do żadnego stanowiska. Twórca zadania albo nie jest „stanowi-\r\nskiem”, do którego domyślnie przypisuje się zadania w trakcie jego tworzenia, albo celowo utworzył\r\nzadanie nie przypisane, które powinien objąć jakiś pracownik samodzielnie lub stanowisko upraw-\r\nnione może takie zadanie przypisać do wybranego stanowiska. Po przypisaniu rekord jest kasowa-\r\nny.\r\n');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabela_id` int(11) NOT NULL,
  `create` bigint(20) NOT NULL,
  `update` bigint(20) NOT NULL DEFAULT '0',
  `delete` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=29 ;

--
-- Zrzut danych tabeli `updates`
--

INSERT INTO `updates` (`id`, `tabela_id`, `create`, `update`, `delete`) VALUES
(1, 1, 0, 0, 0),
(2, 2, 0, 0, 0),
(3, 3, 0, 0, 0),
(4, 4, 0, 0, 0),
(5, 5, 0, 0, 0),
(6, 6, 0, 0, 0),
(7, 7, 0, 0, 0),
(8, 8, 0, 0, 0),
(9, 9, 0, 0, 0),
(10, 10, 0, 0, 0),
(11, 11, 0, 0, 0),
(12, 12, 0, 0, 0),
(13, 13, 0, 0, 0),
(14, 14, 0, 0, 0),
(15, 15, 0, 0, 0),
(16, 16, 0, 0, 0),
(17, 17, 0, 0, 0),
(18, 18, 0, 0, 0),
(19, 19, 0, 0, 0),
(20, 20, 0, 0, 0),
(21, 21, 0, 0, 0),
(22, 22, 0, 0, 0),
(23, 23, 0, 0, 0),
(24, 24, 0, 0, 0),
(25, 25, 0, 0, 0),
(26, 26, 0, 0, 0),
(27, 27, 0, 0, 0),
(28, 28, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `uprawnienia_grup`
--

CREATE TABLE IF NOT EXISTS `uprawnienia_grup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabela_id` int(11) NOT NULL,
  `create_right` int(11) NOT NULL DEFAULT '1',
  `read_right` int(11) NOT NULL DEFAULT '1',
  `update_right` int(11) NOT NULL DEFAULT '1',
  `delete_right` int(11) NOT NULL DEFAULT '1',
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=29 ;

--
-- Zrzut danych tabeli `uprawnienia_grup`
--

INSERT INTO `uprawnienia_grup` (`id`, `tabela_id`, `create_right`, `read_right`, `update_right`, `delete_right`, `create`, `update`) VALUES
(1, 1, 0, 1, 0, 0, '2013-10-24 21:50:48', 0),
(2, 2, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(3, 3, 0, 1, 1, 0, '2013-10-24 21:50:48', 0),
(4, 4, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(5, 5, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(6, 6, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(7, 7, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(8, 8, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(9, 9, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(10, 10, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(11, 11, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(12, 12, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(13, 13, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(14, 14, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(15, 15, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(16, 16, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(17, 17, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(18, 18, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(19, 19, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(20, 20, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(21, 21, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(22, 22, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(23, 23, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(24, 24, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(25, 25, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(26, 26, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(27, 27, 1, 1, 1, 1, '2013-10-24 21:50:48', 0),
(28, 28, 1, 1, 1, 1, '2013-10-24 21:50:48', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `zadania`
--

CREATE TABLE IF NOT EXISTS `zadania` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zadanie` int(11) NOT NULL,
  `klient_id` int(11) NOT NULL,
  `stanowisko_id` int(11) NOT NULL,
  `produkt_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `opis` text COLLATE utf8_polish_ci NOT NULL,
  `data_next_step` timestamp NULL DEFAULT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `zadania`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `zadania_firmy`
--

CREATE TABLE IF NOT EXISTS `zadania_firmy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zadanie` int(11) NOT NULL,
  `firma_id` int(11) NOT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `zadania_firmy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `zadania_opis`
--

CREATE TABLE IF NOT EXISTS `zadania_opis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zadanie` int(11) NOT NULL,
  `notatka` text COLLATE utf8_polish_ci NOT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `zadania_opis`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `zarzadcy`
--

CREATE TABLE IF NOT EXISTS `zarzadcy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pracownicy_id` int(11) NOT NULL,
  `aktywny` tinyint(1) NOT NULL DEFAULT '1',
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `zarzadcy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `zarzady`
--

CREATE TABLE IF NOT EXISTS `zarzady` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zarzadca_id` int(11) NOT NULL,
  `firma_id` int(11) NOT NULL,
  `login` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `haslo` char(40) COLLATE utf8_polish_ci NOT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `zarzady`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `zatrudnienia`
--

CREATE TABLE IF NOT EXISTS `zatrudnienia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stanowiska_id` int(11) NOT NULL,
  `pracownik_id` int(11) NOT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `zatrudnienia`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `zespoly`
--

CREATE TABLE IF NOT EXISTS `zespoly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lider_id` int(11) NOT NULL,
  `stanowisko_id` int(11) NOT NULL,
  `data_od` timestamp NULL DEFAULT NULL,
  `data_do` timestamp NULL DEFAULT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `zespoly`
--


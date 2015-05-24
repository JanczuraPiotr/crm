<?php
namespace pjpl\i;
/**
 * Interfejs jest obietnicą, że obiekt go implementujący zawsze będzie utworzony, czyli zmienna typu klasy implementującej interfejs zawsze będzie zainicjowana.
 *
 * Założeniem jest, że obiekt zawsze będzie będzie istniał i dwołanie do niego :
 * <code>
 * $object->...();
 * </code>
 * nie spowodowało błędu a ewentualny test przydatności można wykonać testując warunki:
 * <code>
 *  if( $object->isEmpty() ){
 *    ...
 *  }
 *  // lub
 *  if( $object->notEmpty() ){
 *    ..
 *  }
 * </code>
 *
 * Puki PHP nie umożliwia przeciążania operatorów, spełnienie obiektnicy spoczywa na programiście by nie użył polecenia:
 * <code>
 * $object = null;
 * </code>
 * ale:
 * <code>
 * $object->setEmpty();
 * </code>
 *
 * Niestety nie można pokryć operatora '=' by w momenie wykonania operacji:
 * <code>
 * $obect = null;
 * </code>
 * wykonał się kod:
 * <code>
 *  $objec = new Object();
 * </code>
 * lub:
 * <code>
 *  $object->setEmpty();
 * </code>
 *
 * @confirm 2014-10-18 Utworzenie
 */
interface NeverNull{
	/**
	 * Object nie nadaje się do wykorzystania ale odwołanie do niego nie spowoduje błędu.
	 * @return boolean
	 */
	public function isEmpty();
	/**
	 * Obiekt "istnieje" , jest zainicjowany danymi i nadaje się do użycia
	 * @return boolean
	 */
	public function notEmpty();
	/**
	 * Czyści obiekt z danych. Odwołanie do niego nie przyniesie rezultatu ale też nie spowoduje błędu odwołania do nieistniejącej zmiennej.
	 */
	public function setEpmty();
}
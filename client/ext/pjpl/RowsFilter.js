/**
 * Filtruje grid według parametrów podawanych dla wskazanych kolumn.
 *
 * Obiekt musi być tworzony dopiero po wykonaniu kostruktora rodzica.
 * @param {Ext.grid.Panel} grid
 * @returns {RowsFilter}
 * @done 2014-09-29
 */
var RowsFilter = function(grid) {
	var def = this;
	def.grid = grid;
	def.grid.store.remoteFilter = true;
	def.filterIn = [];
	def.filterOut = [];

	/**
	 * Filtruje dane po stronie servera dla podanej wartości kolumny.
	 *
	 * @param {string} column
	 * @param {string} value
	 */
	def.change = function(column, value){
		var row;
		if(value){
			def.filterIn[column] = value;
		}else{
			delete def.filterIn[column];
		}
		def.filterOut = [];
		for ( row in def.filterIn){
			def.filterOut.push({"property":row,"value":def.filterIn[row],"operator":'like' });
		}
		if( def.filterOut.length === 0){
			def.grid.store.clearFilter();
			def.grid.store.load();
		}else{
			def.grid.store.clearFilter(true);
			def.grid.store.filter(def.filterOut);
		}
	};
};
// @todo Rozbudować filtr by podanie samej spacji jako value szukało rekordów które mają pustą tą kolumnę

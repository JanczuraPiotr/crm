/**
 * Formularz dodawania i edycji danych klienta
 * @work 4.2.0
 */

Ext.define('KlientForm',{
	extend : 'Ext.window.Window',
	alias : 'klient-form',
	title : 'Edycja danych klienta',
	modal : true,
	layout : {
		type : 'anchor'
	},
	width : 650,

	constructor : function(config,recKlient, onRecChange ){
		var def = this;

		def.recKlient = recKlient || null;
		def.onRecChange = onRecChange || function(rec){ console.log(rec); };

		if(def.recKlient === null){
			def.createRecord = true;
		}else{
			def.createRecord = false;
		}
		def.PochodzenieKlientowStore = Ext.create('PochodzenieKlientowStore');
		// @todo nie pojawia się:
		def.PochodzenieKlientowCombo = Ext.create('Ext.form.field.ComboBox',{
			name : 'pochodzenie_klientow_id',
			xtype : 'combobox',
			fieldLabel : 'pochodzenie',
			labelAlign : 'top',
			emptyText : 'Jak pozyskano klienta',
			typeAhead: true,
			triggerAction: 'all',
			selectOnTab: true,
			store:  def.PochodzenieKlientowStore,
			valueField : 'id',
			displayField : 'symbol',
			listClass: 'x-combo-list-small',
			listConfig : {
				getInnerTpl : function(){
					return '{symbol} :<br>{opis}';
				}
			},
			listeners : {
				afterrender : function(This,eOpts){
					if(def.recKlient){
						if(def.recKlient.data.pochodzenie_klientow_id > 0){
							def.PochodzenieKlientowCombo.value = def.recKlient.data.pochodzenie_klientow_id;
						}else{
							this.select();
						}
					}else{
						this.select();
					}
				}
			}
		});

		def.Edytor = Ext.create('Ext.form.field.HtmlEditor',{
			enableSourceEdit : false,
			width : 600,
			height : 335,
			value : def.recKlient.data.opis
		});
		def.callParent(arguments);

		def.Form = def.down('form').getForm();
		if(def.recKlient !== null){
			def.Form.setValues(def.recKlient.data);
			// Czemu nie działa ? def.Edytor.setValue(def.recKlient.data.opis);
		}

	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			items : [
				{
					xtype : 'form',
					bodyPadding : 5,
					defaults : {
						xtype : 'textfield',
						anchor : '100%'
					},
					items : [
						{
							xtype : 'fieldcontainer',
							fieldLabel : 'dane podstawowe',
							layout : 'hbox',
							defaults : {
								xtype : 'textfield',
								labelAlign : 'top'
							},
							items : [
								{
									fieldLabel : 'nazwisko lub nazwa firmy',
									name : 'nazwa',
									inputType : 'textfield',
									maxLength : 50,
									width : 200,
									allowBlank : false
								},{
									fieldLabel : 'imie',
									name : 'imie',
									inputType : 'textfield',
									maxLength : 30,
									width : 100
								},{
									fieldLabel : 'pesel',
									name : 'pesel',
									inputType : 'textfield',
									maxLength : 11,
									width : 100
								},{
									fieldLabel : 'nip',
									name : 'nip',
									inputType : 'textfield',
									maxLength : 15,
									width : 100
								}
							]
						},{
							xtype : 'fieldcontainer',
							fieldLabel : 'adres',
							layout : 'hbox',
							defaults : {
								xtype : 'textfield',
								labelAlign : 'top'
							},
							items : [
								{
									fieldLabel : 'kod poczt',
									name : 'kod_poczt',
									inputType : 'textfield',
									maxLength : 6,
									width : 60
								},{
									fieldLabel : 'miejscowość',
									name : 'miejscowosc',
									inputType : 'textfield'
								},{
									fieldLabel : 'ulica',
									name : 'ul',
									inputType : 'textfield'
								},{
									fieldLabel : 'nr bud',
									name : 'nr_b',
									inputType : 'textfield',
									width : 60
								},{
									fieldLabel : 'nr lok',
									name : 'nr_l',
									inputType : 'textfield',
									width : 60
								}
							]
						},{
							xtype : 'fieldcontainer',
							fieldLabel : 'kontakt',
							layout : 'hbox',
							defaults : {
								xtype : 'textfield',
								labelAlign : 'top'
							},
							items : [
								{
									fieldLabel : 'email',
									name : 'email',
									inputType : 'textfield',
									width : 200
								},{
									fieldLabel : 'tel kom',
									name : 'telkom',
									inputType : 'textfield',
									width : 100
								},{
									fieldLabel : 'tel dom',
									name : 'teldom',
									inputType : 'textfield',
									width : 100
								},{
									fieldLabel : 'tel praca',
									name : 'telpraca',
									inputType : 'textfield',
									width : 100
								}
							]
						},{
							xtype : 'fieldcontainer',
							fieldLabel : 'inne',
							layout : 'hbox',
							defaults : {
								xtype : 'textfield',
								labelAlign : 'top'
							},
							items : [
								def.PochodzenieKlientowCombo,
								{
									xtype : 'datefield',
									fieldLabel : 'data dodania',
									name : 'data_od',
									width : 100
								},{
									xtype : 'datefield',
									fieldLabel : 'data usunięcia',
									name : 'data_do',
									width : 100
								}
							]
						},
						def.Edytor
					]
				}
			],
			dockedItems: [
				{
					xtype: 'toolbar',
					dock: 'bottom',
					items: [
						{
							xtype: 'tbfill'
						},{
							xtype: 'button',
							itemId: 'cancel',
							text: 'Anuluj',
							handler : function(btn){
								def.recKlient = null;
								def.close();
							}
						},{
							xtype: 'button',
							itemId: 'submit',
							formBind: true,
							text: "zapisz",
							handler : function(btn){
								if(def.Form.isValid()){
									var rec = def.Form.getValues();
									def.recKlient.set('nazwa',rec.nazwa);
									def.recKlient.set('imie', rec.imie);
									def.recKlient.set('nip', rec.nip);
									def.recKlient.set('pesel', rec.pesel);
									def.recKlient.set('kod_poczt', rec.kod_poczt);
									def.recKlient.set('miejscowosc', rec.miejscowosc);
									def.recKlient.set('ul', rec.ul);
									def.recKlient.set('nr_b', rec.nr_b);
									def.recKlient.set('nr_l', rec.nr_l);
									def.recKlient.set('email', rec.email);
									def.recKlient.set('telkom', rec.telkom);
									def.recKlient.set('teldom', rec.teldom);
									def.recKlient.set('telpraca', rec.telpraca);
									def.recKlient.set('opis', def.Edytor.getValue() /*rec.opis*/);
									def.recKlient.set('pochodzenie_klientow_id',rec.pochodzenie_klientow_id);
									def.recKlient.set('data_od', rec.data_od);
									def.recKlient.set('data_do', rec.data_do);
									def.close();
								}
							}
						}
					]
				}
			]
		});
		def.callParent(arguments);
	},
	listeners : {
		close : function(panel, eOpts){
			var def = this;
			def.onRecChange(def.recKlient);
		}
	}
});
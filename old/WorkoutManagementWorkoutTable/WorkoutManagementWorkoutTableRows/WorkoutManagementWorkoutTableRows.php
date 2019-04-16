<script type="text/javascript">
var WorkoutManagementWorkoutTableRows = React.createClass({//Workout Management Table Rows
	getInitialState: function () {
		var attrs = JSON.parse(JSON.stringify(this.props.data.attrs));
		return {workout_name:this.props.data.name,comments:this.props.data.comments,attributes:attrs,view:""};
	},
	handleValueChange: function(type,event) {
		switch(type){
			case "workout_name":
				this.setState({workout_name:event.target.value});
				break;
			case "comments":
				this.setState({comments:event.target.value});
				break;
			default:
				console.log('error');
		}
	},
	sendValues: function () {
		serverRequest({query_type:'workout_management_edit',id:this.props.data.id,workout_name:this.state.workout_name,attributes:this.state.attributes,comments:this.state.comments})
		.then(function(data) {
			console.log(data);
		})
		.catch(function(error) {
			console.error("WorkoutManagementWorkoutTableRows.php","workout_management_edit",error);
		});
	},
	handleAttrToggle: function (attr) {
		var attrs = JSON.parse(JSON.stringify(this.state.attributes));
		attrs[attr]=(attrs[attr]=="No")?"Yes":"No";
		this.setState({attributes:attrs},function () {
			this.sendValues();
		});
	},
	deleteToggle: function (type) {
		this.setState({deletetd:type});
	},
	deleteEntry: function () {
		var c = confirm("Are you sure you wish to delete this workout? This will also delete all data of completion of this workout.");
		if(c==true){
			this.setState({view:"hidden"},function () {
				serverRequest({query_type:'workout_management_delete',id:this.props.data.id})
				.then(function(data) {
					console.log(data);
				})
				.catch(function(error) {
					console.error("WorkoutManagementWorkoutTableRows.php","workout_management_delete",error);
				});
			});
		}
	},
	render: function () {
		var cells = [];
		for(var attr in this.state.attributes){
			cells.push(React.createElement("td",{key:"wm-row-"+this.props.data.id+"-attr-"+attr,onClick:this.handleAttrToggle.bind(this,attr)},
				React.createElement("span",null,this.state.attributes[attr])
			));
		}
		return React.createElement("tr",{className:this.props.rowtype+" "+this.state.view},
			React.createElement("td",{className:"inputok"},
				React.createElement("input",{className:"workout-management-table-input",value:this.state.workout_name,type:"text",onChange:this.handleValueChange.bind(this,"workout_name"),onBlur:this.sendValues})
			),
			cells,
			React.createElement("td",null,
				React.createElement("span",null,this.props.data['times_done'])
			),
			React.createElement("td",null,
				React.createElement("span",null,this.props.data['last_done'])
			),
			React.createElement("td",{className:"inputok"},
				React.createElement("textarea",{className:"workout-management-table-comments",value:this.state.comments,onChange:this.handleValueChange.bind(this,"comments"),onBlur:this.sendValues}),
				React.createElement("div",{className:this.state.deletetd},
					React.createElement("div",{className:"workout-management-table-delete-container"},
						React.createElement("i",{className:"fa fa-trash-o",onClick:this.deleteEntry})
					)
				)
			)
		);
	}
});
</script>
import json

## GUI

plan_gui = {
    "Tid": "Plan GUI",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "All",
    "pred": ["START"]
}

make_gui = {
    "Tid": "Make GUI",
    "start": 0,
    "duration": 7,
    "end": 0,
    "responsible": "TBD",
    "pred": ["Plan GUI"]
}

## DDL

ddl_mail = {
    "Tid": "Mail DDL",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Reuben",
    "pred": ["START"]
}

ddl_mailbox = {
    "Tid": "MailBoxes DDL",
    "start": 0,
    "duration": 1,
    "end": 0,
    "responsible": "Reuben",
    "pred": ["Mail DDL"]
}

ddl_branch = {
    "Tid": "Post Office Branch DDL",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Reuben",
    "pred": ["START"]
}

ddl_address = {
    "Tid": "Address DDL",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Reuben",
    "pred": ["START"]
}

ddl_employees = {
    "Tid": "Employees DDL",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Rhi Ann",
    "pred": ["START"]
}

ddl_manager = {
    "Tid": "Manager DDL",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Rhi Ann",
    "pred": ["Employees DDL"]
}

ddl_driver = {
    "Tid": "Driver DDL",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Rhi Ann",
    "pred": ["Employees DDL"]
}

ddl_client = {
    "Tid": "Clients DDL",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Esther",
    "pred": ["START"]
}

ddl_payment = {
    "Tid": "Payment DDL",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Esther",
    "pred": ["START"]
}

ddl_truck = {
    "Tid": "Truck DDL",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Esther",
    "pred": ["START"]
}

## DML

dml_mail = {
    "Tid": "Mail DML",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Reuben",
    "pred": ["Mail DDL"]
}

dml_mailbox = {
    "Tid": "MailBoxes DML",
    "start": 0,
    "duration": 1,
    "end": 0,
    "responsible": "Reuben",
    "pred": ["Mail DML", "MailBoxes DDL"]
}

dml_branch = {
    "Tid": "Post Office Branch DML",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Reuben",
    "pred": ["Post Office Branch DDL"]
}

dml_address = {
    "Tid": "Address DML",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Reuben",
    "pred": ["Address DDL"]
}

dml_employees = {
    "Tid": "Employees DML",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Rhi Ann",
    "pred": ["Employees DDL"]
}

dml_manager = {
    "Tid": "Manager DML",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Rhi Ann",
    "pred": ["Manager DDL", "Employees DML"]
}

dml_driver = {
    "Tid": "Driver DML",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Rhi Ann",
    "pred": ["Driver DDL", "Employees DML"]
}

dml_client = {
    "Tid": "Clients DML",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Esther",
    "pred": ["Clients DDL"]
}

dml_payment = {
    "Tid": "Payment DML",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Esther",
    "pred": ["Payment DDL"]
}

dml_truck = {
    "Tid": "Truck DML",
    "start": 0,
    "duration": 2,
    "end": 0,
    "responsible": "Esther",
    "pred": ["Truck DDL"]
}

## Queries

think_queries = {
    "Tid": "Brainstorm Queries",
    "start": 0,
    "duration": 1,
    "end": 0,
    "responsible": "All",
    "pred": ["Mail DML", 
             "MailBoxes DML", 
             "Post Office Branch DML",
             "Address DML", 
             "Employees DML", 
             "Manager DML", 
             "Driver DML", 
             "Clients DML", 
             "Payment DML",
             "Truck DML"]
}

imp_queries = {
    "Tid": "Implement Queries",
    "start": 0,
    "duration": 7,
    "end": 0,
    "responsible": "TBD",
    "pred": ["Brainstorm Queries"]
}


##

integrate = {
    "Tid": "Integrate GUI/SQL",
    "start": 0,
    "duration": 7,
    "end": 0,
    "responsible": "TBD",
    "pred": ["Implement Queries", "Make GUI"]
}

optimize = {
    "Tid": "Optimize/Bug Fix",
    "start": 0,
    "duration": 4,
    "end": 0,
    "responsible": "All",
    "pred": ["Integrate GUI/SQL"]
}

end = {
    "Tid": "END",
    "start": 0,
    "duration": 0,
    "end": 0,
    "responsible": "",
    "pred": ["Optimize/Bug Fix"]
  }


tasks = {
    "Plan GUI": plan_gui,
    "Make GUI": make_gui,
    "Mail DDL": ddl_mail,
    "MailBoxes DDL": ddl_mailbox, 
    "Post Office Branch DDL": ddl_branch,
    "Address DDL": ddl_address,
    "Employees DDL": ddl_employees,
    "Manager DDL": ddl_manager,
    "Driver DDL": ddl_driver,
    "Clients DDL": ddl_client,
    "Payment DDL": ddl_payment,
    "Truck DDL": ddl_truck,
    "Mail DML": dml_mail,
    "MailBoxes DML": dml_mailbox, 
    "Post Office Branch DML": dml_branch,
    "Address DML": dml_address,
    "Employees DML": dml_employees,
    "Manager DML": dml_manager,
    "Driver DML": dml_driver,
    "Clients DML": dml_client,
    "Payment DML": dml_payment,
    "Truck DML": dml_truck,
    "Brainstorm Queries": think_queries,
    "Implement Queries": imp_queries,
    "Integrate GUI/SQL": integrate,
    "Optimize/Bug Fix": optimize,
    "END": end
}


with open("./Python/tasks.json", "w") as outfile:
    json.dump(tasks, outfile)
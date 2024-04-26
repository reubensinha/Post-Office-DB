import os
import create_json
import plotly.express as px
import pandas as pd
import graphviz
from pertchart import PertChart

os.chdir("./Python/")


def pert():
    pc = PertChart()
    tasks = pc.getInput("./tasks.json")
    pc.create_pert_chart(pc.calculate_values(tasks))
    
    fig = graphviz.Source.from_file('PERT.gv')
    fig.format = 'svg'
    fig.render("PERT.gv")
    
    
def gantt():
    df = pd.DataFrame([
        dict(Task="Plan GUI", Start='2024-03-11', Finish='2024-03-13', Resource="Plan GUI", Type="Other", width=0.5),
        dict(Task="Plan GUI max", Start='2024-03-11', Finish='2024-03-18', Resource="Plan GUI", Type="Other", width=0.2),
        ## DDL
        dict(Task="Mail DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Mail DDL", Type="DDL", width=0.5),
        dict(Task="Mail DDL max", Start='2024-03-11', Finish='2024-03-17', Resource="Mail DDL", Type="DDL", width=0.2),
        dict(Task="MailBoxes DDL", Start='2024-03-13', Finish='2024-03-14', Resource="MailBoxes DDL", Type="DDL", width=0.5),
        dict(Task="MailBoxes DDL max", Start='2024-03-13', Finish='2024-03-18', Resource="MailBoxes DDL", Type="DDL", width=0.2),
        dict(Task="Post Office Branch DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Post Office Branch DDL", Type="DDL", width=0.5),
        dict(Task="Post Office Branch DDL max", Start='2024-03-11', Finish='2024-03-18', Resource="Post Office Branch DDL", Type="DDL", width=0.2),
        dict(Task="Address DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Address DDL", Type="DDL", width=0.5),
        dict(Task="Address DDL max", Start='2024-03-11', Finish='2024-03-18', Resource="Address DDL", Type="DDL", width=0.2),
        dict(Task="Employees DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Employees DDL", Type="DDL", width=0.5),
        dict(Task="Employees DDL max", Start='2024-03-11', Finish='2024-03-16', Resource="Employees DDL", Type="DDL", width=0.2),
        dict(Task="Manager DDL", Start='2024-03-13', Finish='2024-03-15', Resource="Manager DDL", Type="DDL", width=0.5),
        dict(Task="Manager DDL max", Start='2024-03-13', Finish='2024-03-18', Resource="Manager DDL", Type="DDL", width=0.2),
        dict(Task="Driver DDL", Start='2024-03-13', Finish='2024-03-15', Resource="Driver DDL", Type="DDL", width=0.5),
        dict(Task="Driver DDL max", Start='2024-03-13', Finish='2024-03-18', Resource="Driver DDL", Type="DDL", width=0.2),
        dict(Task="Clients DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Clients DDL", Type="DDL", width=0.5),
        dict(Task="Clients DDL max", Start='2024-03-11', Finish='2024-03-18', Resource="Clients DDL", Type="DDL", width=0.2),
        dict(Task="Payment DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Payment DDL", Type="DDL", width=0.5),
        dict(Task="Payment DDL max", Start='2024-03-11', Finish='2024-03-18', Resource="Payment DDL", Type="DDL", width=0.2),
        dict(Task="Truck DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Truck DDL", Type="DDL", width=0.5),
        dict(Task="Truck DDL max", Start='2024-03-11', Finish='2024-03-18', Resource="Truck DDL", Type="DDL", width=0.2),
        ## DML
        dict(Task="Mail DML", Start='2024-03-13', Finish='2024-03-15', Resource="Mail DML", Type="DML", width=0.5),
        dict(Task="Mail DML max", Start='2024-03-13', Finish='2024-03-24', Resource="Mail DML", Type="DML", width=0.2),
        dict(Task="MailBoxes DML", Start='2024-03-15', Finish='2024-03-16', Resource="MailBoxes DML", Type="DML", width=0.5),
        dict(Task="MailBoxes DML max", Start='2024-03-15', Finish='2024-03-25', Resource="MailBoxes DML", Type="DML", width=0.2),
        dict(Task="Post Office Branch DML", Start='2024-03-13', Finish='2024-03-15', Resource="Post Office Branch DML", Type="DML", width=0.5),
        dict(Task="Post Office Branch DML max", Start='2024-03-13', Finish='2024-03-25', Resource="Post Office Branch DML", Type="DML", width=0.2),
        dict(Task="Address DML", Start='2024-03-13', Finish='2024-03-15', Resource="Address DML", Type="DML", width=0.5),
        dict(Task="Address DML max", Start='2024-03-13', Finish='2024-03-25', Resource="Address DML", Type="DML", width=0.2),
        dict(Task="Employees DML", Start='2024-03-13', Finish='2024-03-15', Resource="Employees DML", Type="DML", width=0.5),
        dict(Task="Employees DML max", Start='2024-03-13', Finish='2024-03-23', Resource="Employees DML", Type="DML", width=0.2),
        dict(Task="Manager DML", Start='2024-03-15', Finish='2024-03-17', Resource="Manager DML", Type="DML", width=0.5),
        dict(Task="Manager DML max", Start='2024-03-15', Finish='2024-03-25', Resource="Manager DML", Type="DML", width=0.2),
        dict(Task="Driver DML", Start='2024-03-15', Finish='2024-03-17', Resource="Driver DML", Type="DML", width=0.5),
        dict(Task="Driver DML max", Start='2024-03-15', Finish='2024-03-25', Resource="Driver DML", Type="DML", width=0.2),
        dict(Task="Clients DML", Start='2024-03-13', Finish='2024-03-15', Resource="Clients DML", Type="DML", width=0.5),
        dict(Task="Clients DML max", Start='2024-03-13', Finish='2024-03-25', Resource="Clients DML", Type="DML", width=0.2),
        dict(Task="Payment DML", Start='2024-03-13', Finish='2024-03-15', Resource="Payment DML", Type="DML", width=0.5),
        dict(Task="Payment DML max", Start='2024-03-13', Finish='2024-03-25', Resource="Payment DML", Type="DML", width=0.2),
        dict(Task="Truck DML", Start='2024-03-13', Finish='2024-03-15', Resource="Truck DML", Type="DML", width=0.5),
        dict(Task="Truck DML max", Start='2024-03-13', Finish='2024-03-25', Resource="Truck DML", Type="DML", width=0.2),
        ##
        dict(Task="Brainstorm Queries", Start='2024-03-17', Finish='2024-03-18', Resource="Brainstorm Queries", Type="Other", width=0.5),
        dict(Task="Brainstorm Queries max", Start='2024-03-17', Finish='2024-03-18', Resource="Brainstorm Queries", Type="Other", width=0.2),
        dict(Task="Implement Queries", Start='2024-03-18', Finish='2024-03-25', Resource="Implement Queries", Type="Other", width=0.5),
        dict(Task="Implement Queries max", Start='2024-03-18', Finish='2024-03-25', Resource="Implement Queries", Type="Other", width=0.2),
        ##
        dict(Task="Make GUI", Start='2024-03-13', Finish='2024-03-20', Resource="Make GUI", Type="Other", width=0.5),
        dict(Task="Make GUI max", Start='2024-03-13', Finish='2024-04-01', Resource="Make GUI", Type="Other", width=0.2),
        dict(Task="Integrate GUI/SQL", Start='2024-03-25', Finish='2024-04-01', Resource="Integrate GUI/SQL", Type="Other", width=0.5),
        dict(Task="Integrate GUI/SQL max", Start='2024-03-25', Finish='2024-04-05', Resource="Integrate GUI/SQL", Type="Other", width=0.2),
        dict(Task="Optimize/Bug Fix", Start='2024-04-01', Finish='2024-04-05', Resource="Optimize/Bug Fix", Type="Other", width=0.5),
        dict(Task="Optimize/Bug Fix max", Start='2024-04-01', Finish='2024-04-05', Resource="Optimize/Bug Fix", Type="Other", width=0.2),
    ])
    
    
    fig = px.timeline(df, x_start="Start", x_end="Finish", y="Resource", color="Type", opacity=0.5)
    
    fig.update_yaxes(autorange="reversed") # otherwise tasks are listed from the bottom up
    fig.show()
    
    fig.to_html(full_html=False, include_plotlyjs='cdn')
    fig.write_html("gantt.html", full_html=False, include_plotlyjs='cdn')
    
    fig.write_image("gantt.svg")

    
    
def schedule():
    df = pd.DataFrame([
        dict(Task="Plan GUI", Start='2024-03-11', Finish='2024-03-13', Resource="All", Type="Other"),
        ## DDL
        dict(Task="Mail DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Reuben", Type="DDL"),
        dict(Task="MailBoxes DDL", Start='2024-03-13', Finish='2024-03-14', Resource="Reuben", Type="DDL"),
        dict(Task="Post Office Branch DDL", Start='2024-03-14', Finish='2024-03-16', Resource="Reuben", Type="DDL"),
        dict(Task="Address DDL", Start='2024-03-16', Finish='2024-03-18', Resource="Reuben", Type="DDL"),
        dict(Task="Employees DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Rhi Ann", Type="DDL"),
        dict(Task="Manager DDL", Start='2024-03-13', Finish='2024-03-15', Resource="Rhi Ann", Type="DDL"),
        dict(Task="Driver DDL", Start='2024-03-15', Finish='2024-03-17', Resource="Rhi Ann", Type="DDL"),
        dict(Task="Clients DDL", Start='2024-03-11', Finish='2024-03-13', Resource="Esther", Type="DDL"),
        dict(Task="Payment DDL", Start='2024-03-13', Finish='2024-03-15', Resource="Esther", Type="DDL"),
        dict(Task="Truck DDL", Start='2024-03-15', Finish='2024-03-17', Resource="Esther", Type="DDL"),
        ## DML
        dict(Task="Mail DML", Start='2024-03-18', Finish='2024-03-20', Resource="Reuben", Type="DML"),
        dict(Task="MailBoxes DML", Start='2024-03-20', Finish='2024-03-21', Resource="Reuben", Type="DML"),
        dict(Task="Post Office Branch DML", Start='2024-03-21', Finish='2024-03-23', Resource="Reuben", Type="DML"),
        dict(Task="Address DML", Start='2024-03-23', Finish='2024-03-25', Resource="Reuben", Type="DML"),
        dict(Task="Employees DML", Start='2024-03-18', Finish='2024-03-20', Resource="Rhi Ann", Type="DML"),
        dict(Task="Manager DML", Start='2024-03-20', Finish='2024-03-22', Resource="Rhi Ann", Type="DML"),
        dict(Task="Driver DML", Start='2024-03-22', Finish='2024-03-24', Resource="Rhi Ann", Type="DML"),
        dict(Task="Clients DML", Start='2024-03-18', Finish='2024-03-20', Resource="Esther", Type="DML"),
        dict(Task="Payment DML", Start='2024-03-20', Finish='2024-03-22', Resource="Esther", Type="DML"),
        dict(Task="Truck DML", Start='2024-03-22', Finish='2024-03-24', Resource="Esther", Type="DML"),
        ##
        dict(Task="Brainstorm Queries", Start='2024-03-18', Finish='2024-03-19', Resource="All", Type="Other"),
        dict(Task="Implement Queries", Start='2024-03-19', Finish='2024-03-25', Resource="TBD", Type="Other"),
        ##
        dict(Task="Make GUI", Start='2024-03-25', Finish='2024-04-01', Resource="TBD", Type="Other"),
        dict(Task="Integrate GUI/SQL", Start='2024-04-01', Finish='2024-04-05', Resource="TBD", Type="Other"),
        dict(Task="Optimize/Bug Fix", Start='2024-04-01', Finish='2024-04-05', Resource="All", Type="Other"),
    ])
    
    fig = px.timeline(df, x_start="Start", x_end="Finish", y="Resource", color="Type")
    
    fig.update_yaxes(autorange="reversed") # otherwise tasks are listed from the bottom up
    fig.show()
    
    fig.to_html(full_html=False, include_plotlyjs='cdn')
    fig.write_html("schedule.html", full_html=False, include_plotlyjs='cdn')
    
    fig.write_image("schedule.svg")


def main():
    pert()
    gantt()
    schedule()
    
    
main()
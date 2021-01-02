create table student (
    rollnumber int,
    password varchar(40),
    room varchar(8),
    floor tinyint(4),
    hostel varchar(5),
    primary key (rollnumber)
);

create table admin (
    admin_id int,
    username varchar(30),
    password varchar(30),
    hostel varchar(5),
    primary key (admin_id)
);

create table housekeeper (
    worker_id int,
    name varchar(30),
    hostel varchar(5),
    floor tinyint(4),
    rooms_cleaned int(5),
    complaints tinyint(4),
    primary key (worker_id)
);

create table cleanrequest (
    request_id int(12),
    rollnumber int(12),
    worker_id int(12),
    date date,
    cleaningtime time,
    req_status boolean,
    primary key (request_id),
    foreign key (rollnumber) references student(rollnumber) on delete cascade,
    foreign key (worker_id) references housekeeper(worker_id) on delete cascade
);


create table feedback (
    feedback_id int(12),
    rollnumber int(12),
    request_id int(12),
    rating tinyint(2),
    timein time,
    timneout time,
    primary key (feedback_id),
    foreign key (rollnumber) references student(rollnumber) on delete cascade,
    foreign key (request_id) references cleanrequest(request_id) on delete cascade
);

create table complaints (
    complaint_id int(12),
    feedback_id int(12),
    rollnumber int(12),
    complaint varchar(200),
    primary key (complaint_id),
    foreign key (feedback_id) references feedback(feedback_id) on delete cascade,
    foreign key (rollnumber) references student(rollnumber) on delete cascade
);


create table suggestions (
    suggestion_id int(12),
    feedback_id int(12),
    rollnumber int(12),
    suggestion varchar(200),
    primary key (suggestion_id),
    foreign key (feedback_id) references feedback(feedback_id) on delete cascade,
    foreign key (rollnumber) references student(rollnumber) on delete cascade
);


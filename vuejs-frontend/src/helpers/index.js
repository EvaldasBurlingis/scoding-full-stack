export function formatStatus(status) {
    let formatted;
    switch(status) {
        case 'to_do': 
            formatted = 'To-do';
        break;
        case 'in_progress':
            formatted = 'In progress';
        break;
        case 'completed':
            formatted = 'Completed';
        break;
        default:
            formatted = 'Status';
        break;
    }
    
    return formatted;
}

export function countTasksByStatus(status, arr) {
    return arr.filter(item => item.status === status).length;
}
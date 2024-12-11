new Vue({
    el: '#app',
    data: {
        ticket: {
            departure_time: '',
            source: '',
            destination: '',
            passport_id: ''
        },
        cancelTicketId: '',
        responseMessage: ''
    },
    methods: {
        createTicket() {
            fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(this.ticket)
            })
            .then(response => {
                return response.json().then(data => {
                    if (!response.ok) {
                        throw new Error(data.error || 'Failed to create ticket');
                    }
                    return data;
                });
            })
            .then(data => {
                this.responseMessage = `Ticket created:\n${JSON.stringify(data, null, 2)}`;
                this.resetForm();
            })
            .catch(error => {
                this.responseMessage = `Error: ${error.message}`;
            });
        },
        cancelTicket() {
            fetch(`api.php/tickets/${this.cancelTicketId}`, {
                method: 'DELETE',
            })
            .then(response => {
                return response.json().then(data => {
                    if (!response.ok) {
                        throw new Error(data.error || 'Failed to cancel ticket');
                    }
                    return data;
                });
            })
            .then(data => {
                this.responseMessage = data.message;
                this.cancelTicketId = '';
            })
            .catch(error => {
                this.responseMessage = `Error: ${error.message}`;
            });
        },
        resetForm() {
            this.ticket = {
                departure_time: '',
                source: '',
                destination: '',
                passport_id: ''
            };
        }
    }
});
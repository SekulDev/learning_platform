FROM nginx:alpine

RUN rm /etc/nginx/conf.d/*

COPY ./deploy/nginx.conf /etc/nginx/conf.d/

EXPOSE 80
EXPOSE 443

CMD [ "nginx", "-g", "daemon off;" ]

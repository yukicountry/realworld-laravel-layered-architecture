FROM nginx:1.27-alpine
SHELL ["/bin/ash", "-oeux", "pipefail", "-c"]

# change timezone UTC to JST
RUN apk add --update --no-cache tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    echo "Asia/Tokyo" > /etc/timezone && \
    apk del tzdata

COPY ./default.conf /etc/nginx/conf.d/default.conf
